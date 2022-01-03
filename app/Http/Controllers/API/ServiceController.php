<?php

namespace App\Http\Controllers\API;

use App\Service;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;
use Google\Service\Drive\DriveFile;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    public const DRIVE_SCOPES = [
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file'
    ];

    public function connect(Request $request, Client $client)
    {
        if ($request->service === 'google-drive') {
            // $client = new Client();
            // $config = config('services.google-drive');
            // $client->setClientId($config['id']);
            // $client->setClientSecret($config['secret']);
            // $client->setRedirectUri($config['redirect_uri']);
            $client->setScopes(self::DRIVE_SCOPES);
            $url = $client->createAuthUrl();

            return response(['url' => $url]);
        }
    }

    public function callback(Request $request, Client $client)
    {
        // Regular
        // $client = new Client();
        // Mocking
        // $client = app(Client::class);

        // $config = config('services.google-drive');
        // $client->setClientId($config['id']);
        // $client->setClientSecret($config['secret']);
        // $client->setRedirectUri($config['redirect_uri']);
        $code = $request->code;
        $client->setScopes(self::DRIVE_SCOPES);
        $accessToken = $client->fetchAccessTokenWithAuthCode($code);

        $service = Service::create([
            'user_id' => auth()->id(),
            'token' => json_encode(['access_token' => $accessToken]),
            'name' => 'google-drive'
        ]);

        return $service;
    }

    public function upload(Request $request, Service $service, Client $client)
    {
        $accessToken = $service->token['access_token'];
        $client->setAccessToken($accessToken);

        $service = new Drive($client);
        // We'll setup an empty 1MB file to upload.
        DEFINE("TESTFILE", 'testfile-small.txt');
        if (!file_exists(TESTFILE)) {
            $fh = fopen(TESTFILE, 'w');
            fseek($fh, 1024 * 1024);
            fwrite($fh, "!", 1);
            fclose($fh);
        }
        // Now lets try and send the metadata as well using multipart!
        $file = new DriveFile;
        $file->setName("Upload test file from Development");
        $service->files->create(
            $file,
            array(
                'data' => file_get_contents(TESTFILE),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            )
        );

        return response('', Response::HTTP_CREATED);
    }
}
