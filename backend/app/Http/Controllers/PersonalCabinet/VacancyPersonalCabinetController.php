<?php

/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\PersonalCabinet;

use App\Data\RequestData\VacancyData;
use App\Data\ResourceData\VacancyData as VacancyDataResource;
use App\Enums\EntityName;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Events\Notifications;
use App\Http\Services\AbstractPersonalCabinetHelper;
use App\Http\Services\EntityHelper;
use App\Models\Vacancy;
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

class VacancyPersonalCabinetController extends AbstractPersonalCabinetHelper
{
    protected Vacancy $vacancy;

    public function __construct(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;
    }

    public function getMyVacancies(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $cvs = $this->getAllEntities($this->vacancy, EntityType::Vacancy->value, EntityStatus::Active->value);

        return VacancyDataResource::collection($cvs);
    }

    public function edit(int $id): VacancyDataResource
    {
        $vacancy = $this->getOneEntity($this->vacancy, $id, EntityType::Vacancy->value);

        return VacancyDataResource::from($vacancy);
    }

    public function update(VacancyData $data, Vacancy $vacancy): JsonResponse
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
                $phone->entity_type_id = EntityType::Vacancy->value;
                $phone->entity_id = $vacancy->id;
                $phone->save();
            }

            $vacancy->update([
                'title' => $data->title,
                'description' => $data->description,
                'author_id' => $this->user()->profile->id,
                'entity_type_id' => EntityType::Vacancy->value,
                'status_id' => EntityStatus::UnderModeration->value,
            ]);

            /** @var User $user */
            $user = Auth::user();
            $user->notify(new UpdateEntityNotification(EntityName::Vacancy->value, $vacancy->title));
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

    public function createTemporaryVacancy(): JsonResponse
    {
        $this->vacancy->title = '';
        $this->vacancy->description = '';
        $this->vacancy->author_id = $this->user()->profile->id;
        $this->vacancy->entity_type_id = EntityType::Vacancy->value;
        $this->vacancy->status_id = EntityStatus::UnderModeration->value;

        try {
            $this->vacancy->save();

            return response()->json([
                'success' => true,
                'data' => $this->vacancy->id
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
