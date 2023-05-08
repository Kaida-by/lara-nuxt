<?php

namespace App\Data\RequestData;

use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\Mimes;
use Spatie\LaravelData\Attributes\Validation\MimeTypes;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageData extends Data
{
    #[Required, Image, MimeTypes(['image/jpeg', 'image/png']), Mimes(['jpg', 'png', 'jpeg'])]
    public UploadedFile $file;
}
