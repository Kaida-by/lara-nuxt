<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Google\Cloud\Storage\StorageClient;

class CloudController extends Controller
{
    public function store(UploadedFile $uploadedFile, string $newName): string
    {
        $storage = new StorageClient([
            'keyFilePath' => base_path(). '/zhlobin-6e019ecee572.json',
        ]);

        $bucketName = env('GOOGLE_CLOUD_BUCKET');
        $bucket = $storage->bucket($bucketName);
        $filepath = public_path('assets/images/' . $newName);
        $bucket->upload(
            fopen($filepath, 'rb'),
        );

        unlink(public_path('assets/images/' . $newName));

        return "https://storage.googleapis.com/$bucketName/$newName";
    }
}
