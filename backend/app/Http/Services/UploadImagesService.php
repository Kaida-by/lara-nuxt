<?php

namespace App\Http\Services;


use App\Http\Controllers\CloudController;
use App\Models\Article;
use App\Models\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Str;

class UploadImagesService
{
    public static function save(
        int $entity_type,
        int $entity_id,
        array $files = [],
        bool $is_local = true,
        bool $is_new = true,
    ): void
    {
        foreach ($files as $key => $file) {
            try {
                self::upload($file, $entity_type, $entity_id, $key + 1, $is_local, $is_new);
            } catch (\Exception $exception) {
                dd($exception->getMessage());
            }
        }
    }

    public static function upload
    (
        UploadedFile $uploadedFile,
        int $entity_type,
        int $entity_id,
        int $order,
        bool $is_local,
        bool $is_new,
    )
    {
        if (!$is_new) {
            $imageIds = Article::with(['images'])
                ->where(['id' => $entity_id])
                ->get();

            foreach ($imageIds[0]->images as $image) {
                unlink(public_path() . '/../' . $image['src']);
                Image::destroy(['id' => $image['id']]);
            }
        }

        $permittedMimeTypes = ['image/jpeg', 'image/png'];

        if ($uploadedFile->getSize() > 10000000) {
            throw new \Exception('Max file size 10 Mb');
        }

        if (!in_array($uploadedFile->getMimeType(), $permittedMimeTypes)) {
            throw new \Exception('Valid File Format: jpeg, jpg, png');
        }

        $newName = Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();

        $uploadedFile->move(config('filesystems.file_src'), $newName);

        if (!$is_local) {
            $cloud = new CloudController();
            $url = $cloud->store($uploadedFile, $newName);
        } else {
            $url = config('filesystems.file_src_image_path') . $newName;
        }

        $image = new Image();
        $image->slug = self::getOriginalName($uploadedFile);
        $image->original_name = $uploadedFile->getClientOriginalName();
        $image->uuid = Str::uuid();
        $image->src = $url;
        $image->entity_type_id = $entity_type;
        $image->entity_id = $entity_id;
        $image->order = $order;
        $image->is_local = $is_local ? 1 : 0;
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

    public static function update(array $files, int $entity_type, int $entity_id, string $newMainImageUuid)
    {
        return \response()->json([
            'success' => true,
            'data' => $files
        ], 200);

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
