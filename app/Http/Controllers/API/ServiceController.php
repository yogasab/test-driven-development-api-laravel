<?php

namespace App\Http\Controllers\API;

use App\Service;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;
use Google\Service\Drive\DriveFile;
use App\Http\Controllers\Controller;
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

        $accessToken = $client->fetchAccessTokenWithAuthCode($request->code);

        $service = Service::create([
            'user_id' => auth()->id(),
            'token' => json_encode(['access_token' => $accessToken]),
            'name' => 'google-drive'
        ]);

        return $service;
    }

    public function upload(Request $request, Service $service, Client $client)
    {
        // Fetch last 7 days of tasks
        $tasks = Task::where('created_at', '>=', now()->subDays(7))->get();
        // Create json file with the data
        $fileName = '7DaysTasks.json';
        Storage::put("/public/tasks/$fileName", $tasks->toJson());
        // Create zip file from data
        $zip = new ZipArchive();
        $zipFileName = storage_path('app/public/tasks/' . now()->timestamp . '-task.zip');
        if ($zip->open($zipFileName, ZipArchive::CREATE) === true) {
            $zipFilePath = storage_path('app/public/tasks/' . $fileName);
            $zip->addFile($zipFilePath, $fileName);
        }
        $zip->close();

        // Send to Drive
        $accessToken = json_decode($service->token, true);
        $client->setAccessToken($accessToken['access_token']['access_token']);
        $service = new Drive($client);
        // We'll setup an empty 1MB file to upload.
        // Now lets try and send the metadata as well using multipart!
        $file = new DriveFile;
        $file->setName("HelloWorld.zip");
        $service->files->create(
            $file,
            array(
                'data' => file_get_contents($zipFileName),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            )
        );

        return response()->json([
            'success' => true,
            "message" => 'File uploaded successfully'
        ], Response::HTTP_CREATED);
    }
}
