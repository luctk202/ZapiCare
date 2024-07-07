<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FileUpload;
use App\Models\TestResult;
use App\Models\UserContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use ZipArchive;
use App\Models\TestItem;
use App\Models\TestMeasurement;
use App\Models\TestSystem;
use DOMDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessZipJob;
use Carbon\Carbon;

class FileUploadController extends Controller
{

    public function uploadZip(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|mimes:zip|max:10240',
        ]);

        $uploadedFile = $request->file('zip_file');

        // Kiểm tra xem tệp đã tải lên có hợp lệ hay không
        if (!$uploadedFile->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tải file.',
            ]);
        }

        $originalZipFileName = $uploadedFile->getClientOriginalName();
        $fileNameWithoutExtension = pathinfo($originalZipFileName, PATHINFO_FILENAME);
        $filePath = $uploadedFile->storeAs('uploads', $originalZipFileName);

        $currentUser = Auth::user();

        $contact = UserContact::where('name', $fileNameWithoutExtension)
            ->where('user_id', $currentUser->id)
            ->get();

        return response()->json([
            'success' => true,
            'file_path' => $filePath,
            'file_name' => $fileNameWithoutExtension,
            'contact' => $contact,
        ]);
    }

    public function searchUserContact(Request $request)
    {
        $currentUser = Auth::user();

        // Lấy tên cần tìm từ request
        $name = $request->input('name');

        // Nếu không có tên được cung cấp, trả về tất cả user_contact của người dùng hiện tại
        if (!$name) {
            $contacts = UserContact::where('user_id', $currentUser->id)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $contacts,
            ]);
        }

        // Thực hiện query để tìm kiếm theo tên
        $contacts = UserContact::where('user_id', $currentUser->id)
            ->where('name', 'like', '%' . $name . '%')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $contacts,
        ]);
    }



    public function addUserContact(Request $request)
    {
        $currentUser = Auth::user();
        $contact = UserContact::where('phone', $request->input('phone'))
            ->where('user_id', $currentUser->id)
            ->first();

        if ($contact) {
            return response()->json([
                'success' => false,
                'message' => 'Liên hệ đã tồn tại.',
                'contact' => $contact,
            ]);
        }

        $dateOfBirth = Carbon::createFromFormat('d/m/Y', $request->input('date_of_birth'))->format('Y-m-d');
        $age = Carbon::parse($dateOfBirth)->age;

        // Tạo mới UserContact
        $newContactData = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'date_of_birth' => $dateOfBirth,
            'age' => $age,
            'gender' => $request->input('gender'),
            'province_id' => $request->input('province_id'),
            'district_id' => $request->input('district_id'),
            'ward_id' => $request->input('ward_id'),
            'address' => $request->input('address'),
            'user_id' => $currentUser->id,
        ];

        $contact = UserContact::create($newContactData);

        $contact->date_of_birth = Carbon::parse($contact->date_of_birth)->format('d/m/Y');

        return response()->json([
            'success' => true,
            'contact' => $contact,
        ]);
    }


    public function linkUserContact(Request $request)
    {

        $request->validate([
            'file_path' => 'required|string',
            'contact_id' => 'required|exists:user_contacts,id',
        ]);

        $currentUser = Auth::user();
//        dd($currentUser);
        $contact = UserContact::where('id', $request->contact_id)
            ->first();
        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Liên hệ không tồn tại.',
            ]);
        }

        $fileUpload = FileUpload::create([
            'original_name' => basename($request->file_path),
            'storage_path' => $request->file_path,
            'file_size' => filesize(storage_path('app/' . $request->file_path)),
            'user_id' => $currentUser->id,
        ]);

        // Cập nhật file_upload_id cho user_contact
        $contact->file_upload_id = $fileUpload->id;
        $contact->save();

        // Xử lý tệp ZIP để cập nhật thông tin user contact
        $this->processZip($request->file_path, $fileUpload->id, $currentUser->id);

        return response()->json([
            'success' => true,
            'message' => 'Tệp đã được liên kết thành công với liên hệ.',
            'file_upload_id' => $fileUpload->id
        ]);
    }


//hang
    private function processZip($filePath, $fileUploadId, $userId)
    {
        $extractPath = storage_path('app/uploads/' . $fileUploadId);
        $zip = new \ZipArchive();
        $fullFilePath = storage_path('app/' . $filePath);

        if (!file_exists($fullFilePath) || !is_readable($fullFilePath)) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đọc file ZIP.',
            ]);
        }

        if ($zip->open($fullFilePath) === true) {
            $zip->extractTo($extractPath);
            $zip->close();

            $extractedFiles = glob($extractPath . '/*.htm');
            $testSystemCache = TestSystem::all()->keyBy('test_item');
            $testItemCache = TestItem::all()->keyBy(function ($item) {
                return $item->test_system_id . '-' . $item->name;
            });

            $batchSize = 100;
            $newMeasuredValues = [];

            DB::beginTransaction();

            try {
                Log::info('Bắt đầu xử lý các tệp.');

                foreach ($extractedFiles as $htmlFile) {
                    Log::info('Đang xử lý tệp: ' . $htmlFile);
                    $htmlContent = file_get_contents($htmlFile);
                    $dom = new \DOMDocument();
                    @$dom->loadHTML($htmlContent);
                    $xpath = new \DOMXPath($dom);

                    $userContactDetails = $this->extractUserContactDetails($xpath);
                    // Cập nhật thông tin UserContact dựa trên file_upload_id
                    $contact = UserContact::where('file_upload_id', $fileUploadId)->first();
                    if ($contact && $userContactDetails) {
                        $contact->body_shape = $userContactDetails['body_shape'];
                        $contact->save();
                    }

                    $titleNodes = $xpath->query('//table//tr/td/font[@size="6"]');

                    foreach ($titleNodes as $titleNode) {
                        $fullText = trim($titleNode->nodeValue);

                        if (preg_match('/\((.*?)\)/', $fullText, $matches)) {
                            $testSystemName = $matches[1];

                            if (!isset($testSystemCache[$testSystemName])) {
                                $testSystemCache[$testSystemName] = TestSystem::create(['test_item' => $testSystemName]);
                            }

                            $testSystem = $testSystemCache[$testSystemName];
                            $rows = $xpath->query('//table//tr');
                            foreach ($rows as $row) {
                                $cells = $row->getElementsByTagName('td');

                                if ($cells->length != 4) {
                                    continue;
                                }

                                $cell0 = trim($cells->item(0)->nodeValue ?? '');
                                $cell1 = trim($cells->item(1)->nodeValue ?? '');
                                $cell2 = trim($cells->item(2)->nodeValue ?? '');

                                if ($cell0 == 'Mục kiểm tra' || $cell1 == 'Chỉ số Bình thường' || $cell2 == 'Chỉ số đo được') {
                                    continue;
                                }

                                $testItemName = $cell0;
                                $range = $cell1;
                                $measuredValue = $cell2;

                                $rangePattern = '/([\d\.]+)\s*-\s*([\d\.]+)/';
                                $rangeMin = null;
                                $rangeMax = null;

                                if (preg_match($rangePattern, $range, $matches)) {
                                    $rangeMin = $matches[1];
                                    $rangeMax = $matches[2];
                                }

                                if ($testItemName && $rangeMin && $rangeMax && $measuredValue) {
                                    $cacheKey = $testSystem->id . '-' . $testItemName;
                                    if (!isset($testItemCache[$cacheKey])) {
                                        $testItemCache[$cacheKey] = TestItem::create([
                                            'test_system_id' => $testSystem->id,
                                            'name' => $testItemName,
                                            'normal_range_min' => $rangeMin,
                                            'normal_range_max' => $rangeMax,
                                        ]);
                                    }
                                    $testItem = $testItemCache[$cacheKey];
                                    $newMeasuredValues[] = [
                                        'test_system_id' => $testSystem->id,
                                        'test_item_id' => $testItem->id,
                                        'file_upload_id' => $fileUploadId,
                                        'user_id' => $userId,
                                        'contact_id' => $contact->id ?? null,
                                        'measured_value' => $measuredValue,
//                                        'normal_range_min' => $rangeMin,
//                                        'normal_range_max' => $rangeMax,
                                    ];

                                    if (count($newMeasuredValues) >= $batchSize) {
                                        DB::table('test_measurements')->insert($newMeasuredValues);
                                        $newMeasuredValues = [];
                                    }
                                }
                            }
                        }
                    }
                }

                if (!empty($newMeasuredValues)) {
                    DB::table('test_measurements')->insert($newMeasuredValues);
                }

                DB::commit();
                Log::info('Xử lý hoàn tất. Giao dịch đã được commit.');

                return response()->json([
                    'success' => true,
                    'message' => 'File đã được tải lên và xử lý thành công',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Đã có lỗi xảy ra: ' . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage(),
                ]);
            }
        } else {
            Log::error('Lỗi khi mở file ZIP.', [
                'file' => $filePath,
                'full_path' => $fullFilePath,
            ]);

            Storage::delete($filePath);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi mở file ZIP.',
            ]);
        }
    }


    private function extractUserContactDetails($xpath)
    {
        $details = [
//            'name' => '',
//            'gender' => '',
//            'age' => '',
            'body_shape' => '',
//            'execution_time' => ''
        ];

//        $nameNode = $xpath->query('//td[contains(text(), "Tên:")]');
//        if ($nameNode->length > 0) {
//            $details['name'] = trim(str_replace('Tên:', '', $nameNode->item(0)->nodeValue));
//        }
//
//        $genderNode = $xpath->query('//td[contains(text(), "Giới:")]');
//        if ($genderNode->length > 0) {
//            $details['gender'] = trim(str_replace('Giới:', '', $genderNode->item(0)->nodeValue));
//        }
//
//        $ageNode = $xpath->query('//td[contains(text(), "Tuổi:")]');
//        if ($ageNode->length > 0) {
//            $details['age'] = trim(str_replace('Tuổi:', '', $ageNode->item(0)->nodeValue));
//        }

        $bodyShapeNode = $xpath->query('//td[contains(text(), "Hình thể:")]');
        if ($bodyShapeNode->length > 0) {
            $details['body_shape'] = trim(str_replace('Hình thể:', '', $bodyShapeNode->item(0)->nodeValue));
        }

//        $executionTimeNode = $xpath->query('//td[contains(text(), "Thời gian thực hiện:")]');
//        if ($executionTimeNode->length > 0) {
//            $details['execution_time'] = trim(str_replace('Thời gian thực hiện:', '', $executionTimeNode->item(0)->nodeValue));
//        }

        return $details;
    }

}
