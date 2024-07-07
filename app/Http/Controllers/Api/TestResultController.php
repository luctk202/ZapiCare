<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TestItem;
use App\Models\TestResult;
use Illuminate\Http\Request;
use App\Models\TestSystem;
use App\Models\TestMeasurement;
use Illuminate\Support\Facades\DB;
use App\Models\UserContact;

class TestResultController extends Controller
{

//    public function result(Request $request)
//    {
//        $fileUploadId = $request->query('fileUploadId');
//
//        $measurements = TestMeasurement::where('file_upload_id', $fileUploadId)
//            ->with(['testItem', 'testItem.testSystem', 'testItem.sampleData.diseases', 'testItem.sampleData.products'])
//            ->get();
//
//        $contactInformation = UserContact::where('file_upload_id', $fileUploadId)->get()->map(function ($contact) {
//            return [
//                'name' => $contact->name,
//                'gender' => $contact->gender,
//                'age' => $contact->age,
//                'body_shape' => $contact->body_shape,
//                'execution_time' => now(),
//            ];
//        });
//
//        $result = [];
//
//        foreach ($measurements as $measurement) {
//            $sampleData = $measurement->testItem->sampleData->first();
//
//            $explanation = null;
//            $symptom = null;
//            $diseases = [];
//            $advice = null;
//            $suggestedProducts = [];
//
//            if ($sampleData) {
//                $explanation = $sampleData->explanation;
//                $symptom = $sampleData->symptom;
//                $diseases = $sampleData->diseases->map(function ($disease) {
//                    return [
//                        'name' => $disease->name,
////                        'suggested_product_diseases' => $disease->products->map(function ($product) {
////                            return [
////                                'name' => $product->name,
////                                'avatar' => $product->avatar,
////                            ];
////                        })->toArray()
//                    ];
//                })->toArray();
//                $advice = $sampleData->advice;
//                $suggestedProducts = $sampleData->products->map(function ($product) {
//                    return [
//                        'id' => $product->id,
//                        'name' => $product->name,
//                        'avatar' => $product->avatar,
//                    ];
//                })->toArray();
//            }
//            $formattedResult = [
//                'id' => $measurement->id,
//                'test_item' => $measurement->testItem->name,
//                'measured_value' => $measurement->measured_value,
//                'normal_range_min' => $measurement->testItem->normal_range_min,
//                'normal_range_max' => $measurement->testItem->normal_range_max,
//                'mild_low_min' => $measurement->testItem->mild_low_min,
//                'mild_low_max' => $measurement->testItem->mild_low_max,
//                'moderately_low_min' => $measurement->testItem->moderately_low_min,
//                'moderately_low_max' => $measurement->testItem->moderately_low_max,
//                'severity_low_min' => $measurement->testItem->severity_low_min,
//                'severity_low_max' => $measurement->testItem->severity_low_max,
//                'mild_high_min' => $measurement->testItem->mild_high_min,
//                'mild_high_max' => $measurement->testItem->mild_high_max,
//                'moderately_high_min' => $measurement->testItem->moderately_high_min,
//                'moderately_high_max' => $measurement->testItem->moderately_high_max,
//                'severity_high_min' => $measurement->testItem->severity_high_min,
//                'severity_high_max' => $measurement->testItem->severity_high_max,
//                'change_explanation' => $explanation,
//                'symptom' => $symptom,
//                'disease' => $diseases,
//                'advice' => $advice,
//                'suggested_product_test_items' => $suggestedProducts,
//                'follow' => $measurement->follow
//            ];
//
//            $testSystemName = $measurement->testItem->testSystem->test_item;
//            if (!isset($result[$testSystemName])) {
//                $result[$testSystemName] = [
//                    'test_system' => $testSystemName,
//                    'test_items' => [],
//                ];
//            }
//
//            $result[$testSystemName]['test_items'][] = $formattedResult;
//        }
//
//        // Add the count of test items for each test system
//        foreach ($result as &$testSystem) {
//            $testSystem['test_item_count'] = count($testSystem['test_items']);
//        }
//
//        $finalResult = array_values($result);
//
//        return response()->json([
//            'success' => true,
//            'data' => [
//                'contact_information' => $contactInformation,
//                'test_systems' => $finalResult,
//            ],
//        ]);
//    }
//    public function result(Request $request)
//    {
//        $fileUploadId = $request->query('fileUploadId');
//        $follow = $request->query('follow');
//
//        $query = TestMeasurement::where('file_upload_id', $fileUploadId);
//
//        // Nếu có giá trị follow, thêm điều kiện lọc
//        if ($follow !== null) {
//            $query->where('follow', $follow);
//        }
//
//        $measurements = $query->with(['testItem', 'testItem.testSystem', 'testItem.sampleData.diseases', 'testItem.sampleData.products'])
//            ->get();
//
//        $contactInformation = UserContact::where('file_upload_id', $fileUploadId)->get()->map(function ($contact) {
//            return [
//                'name' => $contact->name,
//                'gender' => $contact->gender,
//                'age' => $contact->age,
//                'body_shape' => $contact->body_shape,
//                'execution_time' => now(),
//            ];
//        });
//
//        $result = [];
//
//        foreach ($measurements as $measurement) {
//            $sampleData = $measurement->testItem->sampleData->first();
//
//            $explanation = null;
//            $symptom = null;
//            $diseases = [];
//            $advice = null;
//            $suggestedProducts = [];
//
//            if ($sampleData) {
//                $explanation = $sampleData->explanation;
//                $symptom = $sampleData->symptom;
//                $diseases = $sampleData->diseases->map(function ($disease) {
//                    return [
//                        'name' => $disease->name,
//                    ];
//                })->toArray();
//                $advice = $sampleData->advice;
//                $suggestedProducts = $sampleData->products->map(function ($product) {
//                    return [
//                        'id' => $product->id,
//                        'name' => $product->name,
//                        'avatar' => $product->avatar,
//                    ];
//                })->toArray();
//            }
//
//            $formattedResult = [
//                'id' => $measurement->id,
//                'test_item' => $measurement->testItem->name,
//                'measured_value' => $measurement->measured_value,
//                'normal_range_min' => $measurement->testItem->normal_range_min,
//                'normal_range_max' => $measurement->testItem->normal_range_max,
//                'mild_low_min' => $measurement->testItem->mild_low_min,
//                'mild_low_max' => $measurement->testItem->mild_low_max,
//                'moderately_low_min' => $measurement->testItem->moderately_low_min,
//                'moderately_low_max' => $measurement->testItem->moderately_low_max,
//                'severity_low_min' => $measurement->testItem->severity_low_min,
//                'severity_low_max' => $measurement->testItem->severity_low_max,
//                'mild_high_min' => $measurement->testItem->mild_high_min,
//                'mild_high_max' => $measurement->testItem->mild_high_max,
//                'moderately_high_min' => $measurement->testItem->moderately_high_min,
//                'moderately_high_max' => $measurement->testItem->moderately_high_max,
//                'severity_high_min' => $measurement->testItem->severity_high_min,
//                'severity_high_max' => $measurement->testItem->severity_high_max,
//                'change_explanation' => $explanation,
//                'symptom' => $symptom,
//                'disease' => $diseases,
//                'advice' => $advice,
//                'suggested_product_test_items' => $suggestedProducts,
//                'follow' => $measurement->follow
//            ];
//
//            $testSystemName = $measurement->testItem->testSystem->test_item;
//            if (!isset($result[$testSystemName])) {
//                $result[$testSystemName] = [
//                    'test_system' => $testSystemName,
//                    'test_items' => [],
//                ];
//            }
//
//            $result[$testSystemName]['test_items'][] = $formattedResult;
//        }
//
//        // Add the count of test items for each test system
//        foreach ($result as &$testSystem) {
//            $testSystem['test_item_count'] = count($testSystem['test_items']);
//        }
//
//        $finalResult = array_values($result);
//
//        return response()->json([
//            'success' => true,
//            'data' => [
//                'contact_information' => $contactInformation,
//                'test_systems' => $finalResult,
//            ],
//        ]);
//    }
    public function result(Request $request)
    {
        $fileUploadId = $request->query('fileUploadId');
        $follow = $request->query('follow');

        $query = TestMeasurement::where('file_upload_id', $fileUploadId);

        // Nếu có giá trị follow, thêm điều kiện lọc
        if ($follow !== null) {
            $query->where('follow', $follow);
        }

        $measurements = $query->with(['testItem', 'testItem.testSystem', 'testItem.sampleData.diseases', 'testItem.sampleData.products'])
            ->get();

        $contactInformation = UserContact::where('file_upload_id', $fileUploadId)->get()->map(function ($contact) {
            return [
                'name' => $contact->name,
                'gender' => $contact->gender,
                'age' => $contact->age,
                'body_shape' => $contact->body_shape,
                'execution_time' => now(),
            ];
        });

        $result = [];

        foreach ($measurements as $measurement) {
            $sampleData = $measurement->testItem->sampleData->first();

            $explanation = null;
            $symptom = null;
            $diseases = [];
            $advice = null;
            $suggestedProducts = [];

            if ($sampleData) {
                $explanation = $sampleData->explanation;
                $symptom = $sampleData->symptom;
                $diseases = $sampleData->diseases->map(function ($disease) {
                    return [
                        'id'=>$disease->id,
                        'name' => $disease->name,
                    ];
                })->toArray();
                $advice = $sampleData->advice;
                $suggestedProducts = $sampleData->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'avatar' => $product->avatar,
                    ];
                })->toArray();
            }

            $formattedResult = [
                'id' => $measurement->id,
                'test_item' => $measurement->testItem->name,
                'measured_value' => $measurement->measured_value,
                'normal_range_min' => $measurement->testItem->normal_range_min,
                'normal_range_max' => $measurement->testItem->normal_range_max,
                'mild_low_min' => $measurement->testItem->mild_low_min,
                'mild_low_max' => $measurement->testItem->mild_low_max,
                'moderately_low_min' => $measurement->testItem->moderately_low_min,
                'moderately_low_max' => $measurement->testItem->moderately_low_max,
                'severity_low_min' => $measurement->testItem->severity_low_min,
                'severity_low_max' => $measurement->testItem->severity_low_max,
                'mild_high_min' => $measurement->testItem->mild_high_min,
                'mild_high_max' => $measurement->testItem->mild_high_max,
                'moderately_high_min' => $measurement->testItem->moderately_high_min,
                'moderately_high_max' => $measurement->testItem->moderately_high_max,
                'severity_high_min' => $measurement->testItem->severity_high_min,
                'severity_high_max' => $measurement->testItem->severity_high_max,
                'change_explanation' => $explanation,
                'symptom' => $symptom,
                'disease' => $diseases,
                'advice' => $advice,
                'suggested_product_test_items' => $suggestedProducts,
                'follow' => $measurement->follow
            ];

            $testSystemName = $measurement->testItem->testSystem->test_item;
            if (!isset($result[$testSystemName])) {
                $result[$testSystemName] = [
                    'test_system' => $testSystemName,
                    'test_items' => [],
                ];
            }

            $result[$testSystemName]['test_items'][] = $formattedResult;
        }

        // Add the count of test items for each test system
        foreach ($result as &$testSystem) {
            $testSystem['test_item_count'] = count($testSystem['test_items']);
        }

        $finalResult = array_values($result);

        return response()->json([
            'success' => true,
            'data' => [
                'contact_information' => $contactInformation,
                'test_systems' => $finalResult,
            ],
        ]);
    }



    public function follow($id)
    {
        $testMeasurement = TestMeasurement::find($id);

        if (!$testMeasurement) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $testMeasurement->follow = $testMeasurement->follow == 0 ? 1 : 0;
        $testMeasurement->save();

        $message = $testMeasurement->follow == 1 ? 'Đã quan tâm' : 'Bỏ quan tâm';

        return response()->json([
            'message' => $message,
        ]);
    }

}
