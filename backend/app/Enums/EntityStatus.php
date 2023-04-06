<?php

namespace App\Enums;

enum EntityStatus: int
{
    case Active = 1;
    case UnderModeration = 2;
}
