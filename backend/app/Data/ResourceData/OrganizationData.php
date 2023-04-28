<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Data\ResourceData;

use App\Models\Organization;
use App\Models\Phone;
use App\Models\User;
use Spatie\LaravelData\Data;

class OrganizationData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public $status,
        public int $statusId,
        public string $address,
        public null|string $link,
        public Phone $phone,
        public User $user,
    ) {}

    public static function fromModel(Organization $organization): self
    {
        return new self(
            id: $organization->id,
            title: $organization->title,
            description: $organization->description,
            status: $organization->entityStatus,
            statusId: $organization->status_id,
            address: $organization->address,
            link: $organization->link,
            phone: $organization->phone,
            user: $organization->user,
        );
    }
}
