<?php

/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\PersonalCabinet;

use App\Data\RequestData\CVData;
use App\Data\ResourceData\CVData as CVDataResource;
use App\Enums\EntityName;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Events\Notifications;
use App\Http\Services\AbstractPersonalCabinetHelper;
use App\Http\Services\EntityHelper;
use App\Models\CV;
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

class CVPersonalCabinetController extends AbstractPersonalCabinetHelper
{
    protected CV $cv;

    public function __construct(CV $cv)
    {
        $this->cv = $cv;
    }

    public function getMyCVs(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $cvs = $this->getAllEntities($this->cv, EntityType::CV->value, EntityStatus::Active->value);

        return CVDataResource::collection($cvs);
    }

    public function edit(int $id): CVDataResource
    {
        $cv = $this->getOneEntity($this->cv, $id, EntityType::CV->value);

        return CVDataResource::from($cv);
    }

    public function update(CVData $data, CV $cv): JsonResponse
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
                $phone->entity_type_id = EntityType::CV->value;
                $phone->entity_id = $cv->id;
                $phone->save();
            }

            $cv->update([
                'title' => $data->title,
                'description' => $data->description,
                'author_id' => $this->user()->profile->id,
                'entity_type_id' => EntityType::CV->value,
                'status_id' => EntityStatus::UnderModeration->value,
            ]);

            /** @var User $user */
            $user = Auth::user();
            $user->notify(new UpdateEntityNotification(EntityName::CV->value, $cv->title));
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

    public function createTemporaryCV(): JsonResponse
    {
        $this->cv->title = '';
        $this->cv->description = '';
        $this->cv->author_id = $this->user()->profile->id;
        $this->cv->entity_type_id = EntityType::CV->value;
        $this->cv->status_id = EntityStatus::UnderModeration->value;

        try {
            $this->cv->save();

            return response()->json([
                'success' => true,
                'data' => $this->cv->id
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
