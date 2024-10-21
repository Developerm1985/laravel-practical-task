<?php

namespace App\Services;

use Google_Client;
use Google_Service_Sheets;

class GoogleSheetsService
{
    private function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets API Laravel');
        $client->setRedirectUri(env('GOOGLE_SHEET_REDIRECT_URI'));
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setScopes([Google_Service_Sheets::DRIVE, Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/public/credentials.json'));
        $client->setPrompt('select_account consent');
        $token = session()->get('access_token');
        $client->setAccessToken($token);
        return $client;
    }

    public function createSheet($data)
    {
        $client = $this->getClient();
        $service = new Google_Service_Sheets($client);
        $spreadsheet = new \Google_Service_Sheets_Spreadsheet([
            'properties' => [
                'title' => 'Cumulative Sums',
            ],
        ]);
        $spreadsheet = $service->spreadsheets->create($spreadsheet);
        $spreadsheetId = $spreadsheet->spreadsheetId;

        $this->writeDataToSheet($service, $spreadsheetId, $data);
        return $spreadsheetId;
    }

    private function writeDataToSheet($service, $spreadsheetId, $data)
    {
        $range = 'Sheet1!A1';
        $body = new \Google_Service_Sheets_ValueRange([
            'values' => $data,
        ]);
        $params = ['valueInputOption' => 'RAW'];
        $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
    }
}
