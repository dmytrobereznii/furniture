<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use NotificationChannels\Telegram\TelegramChannel;
use Tests\TestCase;

class CallbackTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A recipient who can't be reached must not affect the others or the visitor.
     *
     * @return void
     */
    public function testFailedNotificationDoesNotStopOtherRecipients()
    {
        config(['services.telegram-bot-api.users' => '111,222']);

        $chatIds = [];
        $channel = Mockery::mock(TelegramChannel::class);
        $channel->shouldReceive('send')->andReturnUsing(function ($notifiable) use (&$chatIds) {
            $chatIds[] = $notifiable->telegramChatId;

            if ($notifiable->telegramChatId === '111') {
                throw new Exception('Forbidden: bot was blocked by the user');
            }
        });
        $this->instance(TelegramChannel::class, $channel);

        $response = $this->post('/callback', [
            'name' => 'Test',
            'phone' => '0000000000',
            'message' => 'Test',
        ]);

        $response->assertRedirect('/#contact-form');
        $this->assertSame(['111', '222'], $chatIds);
        $this->assertDatabaseHas('callbacks', ['phone' => '0000000000']);
    }
}
