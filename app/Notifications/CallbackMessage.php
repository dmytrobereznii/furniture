<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class CallbackMessage extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->telegramChatId)
            ->content("Нова заявка!
*Ім'я:* _{$notifiable->name}_
*Номер телефону:* _{$notifiable->phone}_
*Коментар:* _{$notifiable->message}_");
    }
}
