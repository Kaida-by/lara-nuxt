<?php

namespace App\Enums;

enum EntityType: int
{
    case Announcement = 1;
    case Article = 2;
    case Profile = 3;
    case Phones = 4;
    case PhotoFact = 5;
    case Organization = 6;
    case Vacancy = 7;
    case Cv = 8;
    case Poster = 9;
    case PhotoGallery = 10;
    case Static = 11;
}
