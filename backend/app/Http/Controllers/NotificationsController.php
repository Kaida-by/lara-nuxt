<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Http\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationsController
{
    public function getNotifications(Request $request): JsonResponse
    {
        $notifications = NotificationService::getNotification($request->user());

        return response()->json([
            'notifications' => NotificationResource::collection($notifications),
        ]);
    }

    public function removeNotifications(string $notificationUuid, Request $request)
    {
        NotificationService::removeNotifications($notificationUuid, $request->user());
    }

    public function setMarkAsReadNotification(string $notificationUuid, Request $request)
    {
        NotificationService::setMarkAsReadNotification($notificationUuid, $request->user());
    }
}
