<?php

namespace App\Http\Controllers\API;

use App\Service;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;
use Google\Service\Drive\DriveFile;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Services\GoogleDrive;
use App\Services\Zip;
use App\Task;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class ServiceController extends Controller
{
    public const DRIVE_SCOPES = [
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file'
    ];

    // @decs    Create/Store bootcamp
    // @route   POST /api/v1/bootcamps
    // @access  Private
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

    // @decs    Create/Store bootcamp
    // @route   POST /api/v1/bootcamps
    // @access  Private
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

        $accessToken = $client->fetchAccessTokenWithAuthCode($request->code);

        $service = Service::create([
            'user_id' => auth()->id(),
            'token' => $accessToken,
            'name' => 'google-drive'
        ]);

        return $service;
    }

    // @decs    Create/Store bootcamp
    // @route   POST /api/v1/bootcamps
    // @access  Private
    public function upload(Request $request, Service $service, GoogleDrive $googleDrive)
    {
        // Fetch last 7 days of tasks
        $tasks = Task::where('created_at', '>=', now()->subDays(7))->get();
        $zipFileName = Zip::createZipOf($tasks);

        // Send to Drive
        $accessToken = $service->token['access_token'];
        $googleDrive->uploadFile($zipFileName, $accessToken);

        return response()->json([
            'success' => true,
            "message" => 'File uploaded successfully'
        ], Response::HTTP_CREATED);
    }
}
