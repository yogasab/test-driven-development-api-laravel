<?php

namespace App\Services;

use ZipArchive;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Storage;

class Zip
{
  public static function createZipOf($tasks)
  {
    // Create json file with the data
    $fileName = '7DaysTasks.json';
    Storage::put("/public/tasks/$fileName", TaskResource::collection($tasks));

    // Create zip file from data
    $zip = new ZipArchive();
    $zipFileName = storage_path('app/public/tasks/' . now()->timestamp . '-task.zip');
    if ($zip->open($zipFileName, ZipArchive::CREATE) === true) {
      $zipFilePath = storage_path('app/public/tasks/' . $fileName);
      $zip->addFile($zipFilePath, $fileName);
    }
    $zip->close();

    return $zipFileName;
  }
}
