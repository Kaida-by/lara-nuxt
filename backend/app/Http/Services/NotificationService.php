<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace App\Http\Services;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class NotificationService
{
    public static function getNotification(JWTSubject $user): array
    {
        $allNotifications = [];

        foreach ($user->notifications as $notification) {
            $allNotifications[] = $notification;
        }

        return $allNotifications;
    }

    public static function removeNotifications(string $notificationUuid, JWTSubject $user): void
    {
        foreach ($user->notifications as $notification) {
            if ($notification->id === $notificationUuid) {
                $notification->delete();
            }
        }
    }

    public static function setMarkAsReadNotification(string $notificationUuid, JWTSubject $user): void
    {
        foreach ($user->notifications as $notification) {

            if ($notification->id === $notificationUuid) {
                $notification->markAsRead();
            }
        }
    }

    public static function getEntityNameForNotification(string $message): string
    {
        if (strlen($message) > 12) {
            return substr($message, 0 , 12) . '...';
        }

        return $message;
    }
}
