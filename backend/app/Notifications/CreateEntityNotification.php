<?php

namespace App\Notifications;

use App\Http\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CreateEntityNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private string $typeEntity, private string $nameEntity)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'message' => 'Your ' .
                $this->typeEntity . ' "' .
                NotificationService::getEntityNameForNotification($this->nameEntity) .
                '" was created!'
        ];
    }
}
