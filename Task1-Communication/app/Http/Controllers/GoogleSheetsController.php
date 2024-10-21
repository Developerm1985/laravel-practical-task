<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Sheets;
use Illuminate\Http\Request;

class GoogleSheetsController extends Controller
{
    private function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets API Laravel');
        $client->setRedirectUri(route('google.callback'));
        $client->setScopes([Google_Service_Sheets::DRIVE, Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig(public_path('credentials.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $accessToken = session('access_token');
        if ($accessToken) {
            $client->setAccessToken($accessToken);
        }
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                return null;
            }
        }

        return $client;
    }

    public function login()
    {
        $client = $this->getClient();

        if ($client === null) {
            $authClient = new Google_Client();
            $authClient->setAuthConfig(public_path('credentials.json'));
            $authClient->setScopes([Google_Service_Sheets::DRIVE, Google_Service_Sheets::SPREADSHEETS]);
            $authUrl = $authClient->createAuthUrl();
            return redirect($authUrl);
        }

        return to_route('create-sheet');
    }

    public function callback(Request $request)
    {
        $client = $this->getClient();

        if ($request->has('code')) {
            $client = new Google_Client();
            $client->setAuthConfig(public_path('credentials.json'));
            $client->setRedirectUri(route('google.callback'));
            $token = $client->fetchAccessTokenWithAuthCode($request->input('code'));
            session(['access_token' => $token]);
        }

        return redirect()->route('create-sheet');
    }
}
