<?php

/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\PersonalCabinet;

use App\Data\RequestData\OrganizationData;
use App\Data\ResourceData\OrganizationData as OrganizationDataResource;
use App\Enums\EntityName;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Events\Notifications;
use App\Http\Services\AbstractPersonalCabinetHelper;
use App\Http\Services\EntityHelper;
use App\Http\Services\UploadImagesService;
use App\Models\Organization;
use App\Models\Phone;
use App\Models\User;
use App\Notifications\UpdateEntityNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;
use Throwable;
use function response;

class OrganizationPersonalCabinetController extends AbstractPersonalCabinetHelper
{
    protected Organization $organization;

    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
    }

    public function getMyOrganizations(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $organizations = $this->getAllEntities($this->organization, EntityType::Organization->value, EntityStatus::Active->value);

        return OrganizationDataResource::collection($organizations);
    }

    public function edit(int $id): OrganizationDataResource
    {
        $organization = $this->getOneEntity($this->organization, $id, EntityType::Organization->value);

        return OrganizationDataResource::from($organization);
    }

    public function update(OrganizationData $data, Organization $organization): JsonResponse
    {
        try {
            $phoneNumber = EntityHelper::getFinallyPhone($data->phone->number);

            if ($data->phone->id) {
                $phone = Phone::find($data->phone->id);
                $phone->update([
                    'number' => $phoneNumber
                ]);
            } else {
                $phone = new Phone();
                $phone->number = $phoneNumber;
                $phone->entity_type_id = EntityType::Organization->value;
                $phone->entity_id = $organization->id;
                $phone->save();
            }

            $organization->update([
                'title' => $data->title,
                'description' => $data->description,
                'address' => $data->address,
                'link' => $data->link,
                'entity_type_id' => EntityType::Organization->value,
                'status_id' => EntityStatus::UnderModeration->value,
            ]);

            foreach ($data->files as $file) {
                UploadImagesService::upload(
                    $file->file,
                    EntityType::Organization->value,
                    $organization->id,
                    1,
                    true,
                );
            }

            /** @var User $user */
            $user = Auth::user();
            $user->notify(new UpdateEntityNotification(EntityName::Organization->value, $organization->title));
            event(new Notifications($user->id));

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => true,
                'data' => $exception->getMessage()
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => true,
                'data' => $e->getMessage()
            ]);
        }
    }

    public function createTemporaryOrganization(): JsonResponse
    {
        $this->organization->title = '';
        $this->organization->description = '';
        $this->organization->address = '';
        $this->organization->link = '';
        $this->organization->author_id = $this->user()->profile->id;
        $this->organization->entity_type_id = EntityType::Organization->value;
        $this->organization->status_id = EntityStatus::UnderModeration->value;

        try {
            $this->organization->save();

            return response()->json([
                'success' => true,
                'data' => $this->organization->id
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
