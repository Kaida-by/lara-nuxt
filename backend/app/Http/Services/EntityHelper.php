<?php

namespace App\Http\Services;

class EntityHelper
{
    public const TYPE_ANNOUNCEMENT = 1;
    public const TYPE_ARTICLE = 2;
    public const TYPE_PROFILE = 3;
    public const TYPE_PHONES = 4;
    public const TYPE_PHOTO_FACT = 5;
    public const TYPE_ORGANIZATION = 6;
    public const TYPE_VACANCY = 7;
    public const TYPE_CV = 8;
    public const TYPE_POSTERS = 9;
    public const TYPE_PHOTO_GALLERY = 10;
    public const TYPE_STATIC = 11;

    public const ENTITY_STATUS_ACTIVE = 1;
    public const ENTITY_STATUS_UNDER_MODERATION = 2;
}
