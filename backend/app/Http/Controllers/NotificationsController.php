<?php

namespace App\Http\Controllers;

use App\Http\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationsController
{
    public function getNotifications(Request $request): JsonResponse
    {
        $notifications = NotificationService::getNotification($request->user());

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    public function removeNotifications(int $notificationId, Request $request)
    {
        NotificationService::removeNotifications($notificationId, $request->user());
    }
}
