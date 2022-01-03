<?php

namespace App\Http\Controllers\API;

use Google\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service;

class ServiceController extends Controller
{
    public const DRIVE_SCOPES = [
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file'
    ];

    public function connect(Request $request)
    {
        if ($request->service === 'google-drive') {
            $client = new Client();

            $config = config('services.google-drive');
            $client->setClientId($config['id']);
            $client->setClientSecret($config['secret']);
            $client->setRedirectUri($config['redirect_uri']);
            $client->setScopes(self::DRIVE_SCOPES);
            $url = $client->createAuthUrl();

            return response(['url' => $url]);
        }
    }

    public function callback(Request $request)
    {
        // Regular
        // $client = new Client();
        // Mocking
        $client = app(Client::class);
        $code = $request->code;

        $config = config('services.google-drive');
        $client->setClientId($config['id']);
        $client->setClientSecret($config['secret']);
        $client->setRedirectUri($config['redirect_uri']);
        $client->setScopes(self::DRIVE_SCOPES);
        $accessToken = $client->fetchAccessTokenWithAuthCode($code);

        $service = Service::create([
            'user_id' => auth()->id(),
            'token' => json_encode(['access_token' => $accessToken]),
            'name' => 'google-drive'
        ]);

        return $service;
    }
}
