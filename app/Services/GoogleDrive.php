<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleDrive
{

  public $client;

  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  public function uploadFile($zipFileName, $accessToken)
  {
    // Send to Drive
    $this->client->setAccessToken($accessToken);

    $service = new Drive($this->client);
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
  }
}
