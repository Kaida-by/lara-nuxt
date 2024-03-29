<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Services;

use App\Http\Controllers\CloudController;
use App\Http\Interfaces\EntityInterface;
use App\Models\Article;
use App\Models\Image;
use Exception;
use RuntimeException;
use stdClass;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Str;

class UploadImagesService
{
    /**
     * @throws Exception
     */
    public static function save(
        int $entity_type,
        int $entity_id,
        array $files = [],
        bool $is_local = true,
    ): Image
    {
        $is_upload = false;

        foreach ($files as $key => $file) {
            try {
                $is_upload = self::upload($file, $entity_type, $entity_id, $key + 1, $is_local);
            } catch (Exception $exception) {
                throw new RuntimeException($exception);
            }
        }

        return $is_upload;
    }

    /**
     * @throws Exception
     */
    public static function upload
    (
        UploadedFile|stdClass $uploadedFile,
        int $entity_type,
        int $entity_id,
        int $order,
        bool $is_local,
    ): Image
    {
        $permittedMimeTypes = ['image/jpeg', 'image/png'];

        if ($uploadedFile instanceof UploadedFile) {

            if ($uploadedFile->getError()) {
                throw new RuntimeException('Failed to read file');
            }

            if ($uploadedFile->getSize() > 10000000) {
                throw new RuntimeException('Max file size 10 Mb');
            }

            if (!in_array($uploadedFile->getMimeType(), $permittedMimeTypes, true)) {
                throw new RuntimeException('Valid File Format: jpeg, jpg, png');
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

            return $image;
        }

        $image = Image::find($uploadedFile->id);
        $image->order = $order;
        $image->update();

        return $image;

//            $imageIds = Article::with(['images'])
//                ->where(['id' => $entity_id])
//                ->get();
//
//            foreach ($imageIds[0]->images as $image) {
//                unlink(public_path() . '/../' . $image['src']);
//                Image::destroy(['id' => $image['id']]);
//            }
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

//    public static function getSrc(UploadedFile $uploadedFile): string
//    {
//        return config('filesystems.file_src') . $uploadedFile->getClientOriginalName();
//    }

//    public static function update(array $files, int $entity_type, int $entity_id, string $newMainImageUuid)
//    {
//        return \response()->json([
//            'success' => true,
//            'data' => $files
//        ], 200);
//
//        $oldImageMain = Image::where('entity_type_id', $entity_type)
//            ->where('entity_id', $entity_id)
//            ->where('is_main', 1)
//            ->first();
//
//        $newImageMainUuid = explode('&', $newMainImageUuid)[0];
//
//        if ($newImageMainUuid !== $oldImageMain->uuid) {
//            $oldImageId = $oldImageMain->id;
//            $newImageMainId = explode('&', $newMainImageUuid)[1];
//
//            $resetImageMain = Image::find($oldImageId);
//            $resetImageMain->is_main = 0;
//            $resetImageMain->update();
//
//            $image = Image::find($newImageMainId);
//            $image->is_main = 1;
//            $image->update();
//        }
//    }

//    public static function deleteMissingImages (Article $article, array $images): void
//    {
//        $imageIds = array_column($images, 'id');
//        $imagesForDelete = $article->images()->whereNotIn('id', $imageIds);
//
//        foreach ($imagesForDelete->get() as $image) {
//            unlink(public_path() . '/../' . $image->src);
//        }
//
//        $imagesForDelete->delete();
//    }

    public static function getUsedImagesUuidFromHTMLTags(array $tags): array
    {
        $imagesName = [];

        foreach ($tags as $tag) {
            if ($tag['name'] === 'img' && $tag['value']) {
                $path = self::getImageFileNameFromTag($tag['value']);
                $fileName = self::getImageFileUuidFromPath($path);
                $imagesName[] = $fileName;
            } elseif ($tag['value'] && is_int(strpos($tag['value'], '<img'))) {
                $path = self::getImageFileNameFromTag($tag['value']);
                $fileName = self::getImageFileUuidFromPath($path);
                $imagesName[] = $fileName;
            }
        }

        return $imagesName;
    }

    /**
     * @param Article $article
     * @param array $usedImages
     * @return void
     */
    public static function removeUnusedImages(EntityInterface $entity, array $usedImages): void
    {
        $allUserImages = $entity->images;

        /** @var Image $userImage */
        foreach ($allUserImages as $userImage) {
            $uuidImageInDB = self::getImageFileUuidFromPath($userImage->src);

            if (
                !in_array($uuidImageInDB, $usedImages, true) &&
                unlink(public_path() . '/' . $userImage->src)
            ) {
                $userImage->delete();
            }
        }
    }

    /**
     * @param string $tag
     * @return string
     */
    public static function getImageFileNameFromTag(string $tag): string
    {
        preg_match('~(?<=src=")[^"]+(?=")~', $tag, $arr);

        return $arr[0] ?? '';
    }

    /**
     * @param string $path
     * @return string
     */
    public static function getImageFileUuidFromPath(string $path): string
    {
        $partsPath = explode('/', $path);
        $partsName = explode('.', end($partsPath));

        return head($partsName);
    }
}
