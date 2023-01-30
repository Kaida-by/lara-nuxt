<?php

namespace App\Http\Services;

class EntityHelper
{
    const TYPE_ANNOUNCEMENT = 1;
    const TYPE_ARTICLE = 2;
    const TYPE_PROFILE = 3;
    const TYPE_PHONES = 4;
    const TYPE_PHOTO_FACT = 5;
    const TYPE_ORGANIZATION = 6;
    const TYPE_VACANCY = 7;
    const TYPE_CV = 8;
    const TYPE_POSTERS = 9;
    const TYPE_PHOTO_GALLERY = 10;
    const TYPE_STATIC = 11;

    const ENTITY_STATUS_ACTIVE = 1;
    const ENTITY_STATUS_UNDER_MODERATION = 2;
}
