<?php

namespace App\Http\Services;

use App\Models\User;

class NotificationService
{
    /**
     * @param User $user
     */
    public static function getNotification(User $user): array
    {
        $allNotifications = [];

        foreach ($user->unreadNotifications as $notification) {
            $allNotifications[] = $notification['data']['message'];
        }

        return $allNotifications;
    }

    public static function removeNotifications(int $notificationId, User $user): void
    {
        foreach ($user->unreadNotifications as $key => $notification) {
            if ($notificationId === $key) {
                $notification->delete();
            }
        }
    }
}
