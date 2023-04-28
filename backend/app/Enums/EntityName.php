<?php

namespace App\Enums;

enum EntityName: string
{
    case Article = 'article';
    case Poster = 'poster';
    case CV = 'cv';
    case Vacancy = 'vacancy';
    case Organization = 'organization';
}
