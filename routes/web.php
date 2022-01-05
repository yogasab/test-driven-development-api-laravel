<?php

use Illuminate\Support\Facades\Route;
use Google\Client;
use Illuminate\Http\Request;
use Google\Service\Drive\DriveFile;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/google-drive', function () {
    $client = new Client();
    $client->setClientId('90612687484-255grk8su2n1j5tvr0948e8f42kp5af7.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('http://127.0.0.1:8000/google-drive/callback');
    $client->setScopes([
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file'
    ]);
    $url = $client->createAuthUrl();
    return redirect($url);
});

Route::get('/google-drive/callback', function (Request $request) {
    return $request->code;
    // $client = new Client();
    // $code = $request->code;

    // $client->setClientId('90612687484-255grk8su2n1j5tvr0948e8f42kp5af7.apps.googleusercontent.com');
    // $client->setClientSecret('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');
    // $client->setRedirectUri('http://127.0.0.1:8000/google-drive/callback');
    // $accessToken = $client->fetchAccessTokenWithAuthCode($code);

    // return response(['access_token' => $accessToken, 'code' => $code]);
});

Route::get('/upload', function () {
    $client = new Client();
    $accessToken = 'ya29.a0ARrdaM-Y4Q4q3Or-QlVTXdU4BJoJ9o3mSFwlA4HwrwBLh2mDQN6pmxqhUPejpELACy8TgF63xZuyT4J7C7OU-ykP9f1E172U1V9bcqPMB_lywfl9LBbwAKkQYruKnwDxfZ-EMAEaWrH2RZDN5SIHPUGhFb47';

    $client->setAccessToken($accessToken);
    $service = new Google\Service\Drive($client);

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
});
