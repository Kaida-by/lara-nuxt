<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class NotificationService
{
    /**
     * @param User $user
     */
    public static function getNotification(User $user)
    {
        $allNotifications = [];

        foreach ($user->notifications as $notification) {
            $allNotifications[] = $notification;
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

    public static function setMarkAsReadNotification(string $notificationUuid, User $user)
    {
        foreach ($user->notifications as $notification) {

            if ($notification->id === $notificationUuid) {
                $notification->markAsRead();
            }
        }
    }
}
