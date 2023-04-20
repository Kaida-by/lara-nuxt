<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Data\ResourceData;

use App\Models\CV;
use App\Models\Phone;
use App\Models\User;
use Spatie\LaravelData\Data;

class CVData extends Data
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

    public static function fromModel(CV $cv): self
    {
        return new self(
            id: $cv->id,
            title: $cv->title,
            description: $cv->description,
            phone: $cv->phone,
            user: $cv->user,
            status: $cv->entityStatus,
            statusId: $cv->status_id,
            created_at: $cv->created_at,
            updated_at: $cv->updated_at,
        );
    }
}
