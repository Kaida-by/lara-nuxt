<?php

namespace App\Http\Interfaces;

use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

interface PosterInterface
{
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection;
    public function showOne(int $id): Data;
}
