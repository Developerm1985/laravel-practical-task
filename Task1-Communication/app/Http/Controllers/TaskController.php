<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function createGoogleSheet()
    {
        $data = $this->generateData();
        $cumulativeSums = $this->computeCumulativeSums($data);
        $googleSheetsService = new GoogleSheetsService();
        $spreadsheetId = $googleSheetsService->createSheet($cumulativeSums);

        return response()->json(['spreadsheet_id' => $spreadsheetId]);
    }

    private function generateData()
    {
        $data = [];
        for ($i = 0; $i < 10; $i++) {
            $data[$i] = [];
            for ($j = 0; $j < 52; $j++) {
                $data[$i][$j] = mt_rand() / mt_getrandmax();
            }
        }
        return $data;
    }

    private function computeCumulativeSums($data)
    {
        $sums = [];
        foreach ($data as $individual) {
            $cumulative = [];
            $sum = 0;
            foreach ($individual as $value) {
                $sum += $value;
                $cumulative[] = $sum;
            }
            $sums[] = $cumulative;
        }
        return $sums;
    }
}
