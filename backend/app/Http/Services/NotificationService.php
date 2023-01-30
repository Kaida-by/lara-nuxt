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

        foreach ($user->notifications as $notification) {
            $allNotifications[] = $notification;
        }

        return $allNotifications;
    }

    public static function removeNotifications(string $notificationUuid, User $user): void
    {
        foreach ($user->notifications as $notification) {
            if ($notification->id === $notificationUuid) {
                $notification->delete();
            }
        }
    }

    public static function setMarkAsReadNotification(string $notificationUuid, User $user): void
    {
        foreach ($user->notifications as $notification) {

            if ($notification->id === $notificationUuid) {
                $notification->markAsRead();
            }
        }
    }
}
