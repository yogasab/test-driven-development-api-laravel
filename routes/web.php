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
    $config = config('services.google-drive');
    $client->setClientId($config['id']);
    $client->setClientSecret($config['secret']);
    $client->setRedirectUri($config['redirect_uri']);
    $client->setScopes([
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file'
    ]);
    $url = $client->createAuthUrl();
    return redirect($url);
});

Route::get('/google-drive/callback', function (Request $request) {
    $code = $request->code;

    $client = new Client();
    $config = config('services.google-drive');
    $client->setClientId($config['id']);
    $client->setClientSecret($config['secret']);
    $client->setRedirectUri($config['redirect_uri']);
    $accessToken = $client->fetchAccessTokenWithAuthCode($code);

    return response(['access_token' => $accessToken, 'code' => $code]);
});

Route::get('/upload', function () {
    $client = new Client();
    $accessToken = '';
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
