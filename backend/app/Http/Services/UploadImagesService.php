<?php

namespace App\Http\Services;


use App\Http\Controllers\CloudController;
use App\Models\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Str;

class UploadImagesService
{
    public static function save(array $files, int $entity_type, int $entity_id): void
    {
        foreach ($files as $key => $file) {
            try {
                self::upload($file, $entity_type, $entity_id, $key + 1);
            } catch (\Exception $exception) {
                dd($exception->getMessage());
            }
        }
    }

    public static function upload(UploadedFile $uploadedFile, int $entity_type, int $entity_id, int $order): void
    {
        $permittedMimeTypes = ['image/jpeg', 'image/png'];

        if ($uploadedFile->getSize() > 10000000) {
            throw new \Exception('Max file size 10 Mb');
        }

        if (!in_array($uploadedFile->getMimeType(), $permittedMimeTypes)) {
            throw new \Exception('Valid File Format: jpeg, jpg, png');
        }

        $cloud = new CloudController();
        $newName = Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();

        $uploadedFile->move(config('filesystems.file_src'), $newName);
        $couldUrl = $cloud->store($uploadedFile, $newName);

        $image = new Image();
        $image->slug = self::getOriginalName($uploadedFile);
        $image->original_name = $uploadedFile->getClientOriginalName();
        $image->uuid = Str::uuid();
        $image->src = $couldUrl;
        $image->entity_type_id = $entity_type;
        $image->entity_id = $entity_id;
        $image->order = $order;
        $image->save();
    }

    public static function getOriginalName(UploadedFile $uploadedFile): string
    {
        $originalName = stristr(
            $uploadedFile->getClientOriginalName(),
            '.' . $uploadedFile->getClientOriginalExtension(),
            true
        );

        return Str::slug($originalName);
    }

    public static function getSrc(UploadedFile $uploadedFile): string
    {
        $publicPath = config('filesystems.file_src');
        $fileName = $uploadedFile->getClientOriginalName();

        return "{$publicPath}{$fileName}";
    }

    public static function update(int $entity_type, int $entity_id, string $newMainImageUuid)
    {
        $oldImageMain = Image::where('entity_type_id', $entity_type)
            ->where('entity_id', $entity_id)
            ->where('is_main', 1)
            ->first();

        $newImageMainUuid = explode('&', $newMainImageUuid)[0];

        if ($newImageMainUuid !== $oldImageMain->uuid) {
            $oldImageId = $oldImageMain->id;
            $newImageMainId = explode('&', $newMainImageUuid)[1];

            $resetImageMain = Image::find($oldImageId);
            $resetImageMain->is_main = 0;
            $resetImageMain->update();

            $image = Image::find($newImageMainId);
            $image->is_main = 1;
            $image->update();
        }
    }
}
