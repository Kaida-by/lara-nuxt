<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Http\Services\NotificationService;
use Illuminate\Http\JsonResponse;

class NotificationsController extends Controller
{
    public function getNotifications(): JsonResponse
    {
        $notifications = NotificationService::getNotification($this->user());

        return response()->json([
            'notifications' => NotificationResource::collection($notifications),
        ]);
    }

    public function removeNotifications(string $notificationUuid): void
    {
        NotificationService::removeNotifications($notificationUuid, $this->user());
    }

    public function setMarkAsReadNotification(string $notificationUuid): void
    {
        NotificationService::setMarkAsReadNotification($notificationUuid, $this->user());
    }
}
