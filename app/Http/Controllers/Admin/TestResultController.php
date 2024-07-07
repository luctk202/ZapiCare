<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestItem;
use App\Models\TestResult;
use Illuminate\Http\Request;

class TestResultController extends Controller
{
//    public function result()
//    {
//        // Lấy tất cả các TestResult
//        $testResults = TestResult::all();
//        // Mảng để lưu trữ các kết quả thỏa mãn điều kiện
//        $matchingResults = [];
//
//        // Lặp qua từng TestResult
//        foreach ($testResults as $testResult) {
//            // Lấy TestItem dựa trên tên test_item từ test_result
//            $testItem = TestItem::where('name', $testResult->test_item)->first();
//
//            if ($testItem) {
//                // Xác định level_id dựa trên measured_value và các giá trị ngưỡng trong test_item
//                $sampleData = $this->getSampleData($testResult->measured_value, $testItem);
//
//                if ($sampleData) {
//                    $level = $sampleData->level;
//                    $normalRange = $this->getNormalRange($testItem);
//                    $matchingResults[] = [
//                        'test_system' => $testItem->testSystem->test_item,
//                        'test_item' => $testItem->name,
//                        'measured_value' => $testResult->measured_value,
//                        'normal_range_min' => $normalRange['min'],
//                        'normal_range_max' => $normalRange['max'],
//                        'level' => $level->name,
//                        'level_image' => $level->level_image,
//                        'change_explanation' => $sampleData->explanation,
//                        'symptom' => $sampleData->symptom,
//                        'disease' => $sampleData->disease,
//                        'advice' => $sampleData->advice,
//                    ];
//                }
//            }
//        }
//
//        return response()->json($matchingResults);
//    }
//
//    private function getSampleData($measuredValue, $testItem)
//    {
//        // Lấy dữ liệu mẫu từ bảng sample_data
//        foreach ($testItem->sampleData as $sampleData) {
//            if ($measuredValue >= $sampleData->range_min && $measuredValue <= $sampleData->range_max) {
//                return $sampleData;
//            }
//        }
//        return null;
//    }
//
//    private function getNormalRange($testItem)
//    {
//        // Lấy phạm vi bình thường từ bảng sample_data
//        $normalRange = $testItem->sampleData()->where('is_normal_range', true)->first();
//        return [
//            'min' => $normalRange->range_min,
//            'max' => $normalRange->range_max
//        ];
//    }

    public function getResultsByFileUpload($fileUploadId)
    {
        // Lấy tất cả các TestItem có liên quan đến file_upload_id
        $testItems = TestItem::whereHas('measurements', function ($query) use ($fileUploadId) {
            $query->where('file_upload_id', $fileUploadId);
        })->with(['testSystem', 'sampleData', 'measurements' => function($query) use ($fileUploadId) {
            $query->where('file_upload_id', $fileUploadId);
        }])->get();
        // Định dạng kết quả
        $result = [];
        foreach ($testItems as $testItem) {
            $testSystemName = $testItem->testSystem->name;

            if (!isset($result[$testSystemName])) {
                $result[$testSystemName] = [
                    'test_system' => $testSystemName,
                    'test_items' => []
                ];
            }

            foreach ($testItem->measurements as $measurement) {
                $sampleData = $this->getSampleData($measurement->measured_value, $testItem);
                $result[$testSystemName]['test_items'][] = [
                    'test_item' => $testItem->name,
                    'measured_value' => $measurement->measured_value,
                    'range_min' => $measurement->range_min,
                    'range_max' => $measurement->range_max,
//                    'level' => $sampleData ? $sampleData->level->name : null,
//                    'level_image' => $sampleData ? $sampleData->level->level_image : null,
                    'change_explanation' => $sampleData ? $sampleData->explanation : null,
                    'symptom' => $sampleData ? $sampleData->symptom : null,
                    'disease' => $sampleData ? $sampleData->disease : null,
                    'advice' => $sampleData ? $sampleData->advice : null,
                ];
            }
        }

        return response()->json(['data' => array_values($result)]);
    }
        private function getSampleData($measuredValue, $testItem)
    {
        // Lấy dữ liệu mẫu từ bảng sample_data
        foreach ($testItem->sampleData as $sampleData) {
            if ($measuredValue >= $sampleData->range_min && $measuredValue <= $sampleData->range_max) {
                return $sampleData;
            }
        }
        return null;
    }
}
