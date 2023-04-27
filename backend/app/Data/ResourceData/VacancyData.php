<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Data\ResourceData;

use App\Models\Phone;
use App\Models\User;
use App\Models\Vacancy;
use Spatie\LaravelData\Data;

class VacancyData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public Phone $phone,
        public User $user,
        public $status,
        public int $statusId,
        public string $created_at,
        public string $updated_at,
    ) {}

    public static function fromModel(Vacancy $vacancy): self
    {
        return new self(
            id: $vacancy->id,
            title: $vacancy->title,
            description: $vacancy->description,
            phone: $vacancy->phone,
            user: $vacancy->user,
            status: $vacancy->entityStatus,
            statusId: $vacancy->status_id,
            created_at: $vacancy->created_at,
            updated_at: $vacancy->updated_at,
        );
    }
}
