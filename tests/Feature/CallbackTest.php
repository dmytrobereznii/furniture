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

    private const SUCCESS_URL = '/#contact-form';

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

        $response = $this->post('/callback', $this->validForm());

        $response->assertRedirect(self::SUCCESS_URL);
        $this->assertSame(['111', '222'], $chatIds);
        $this->assertDatabaseHas('callbacks', ['phone' => '0000000000']);
    }

    /**
     * @return void
     */
    public function testFormPageContainsSpamProtectionFields()
    {
        $this->get('/')
            ->assertSee('name="website"', false)
            ->assertSee('name="started_at"', false);
    }

    /**
     * Ordinary comments, including ones with dots and numbers, must be accepted.
     *
     * @dataProvider genuineMessages
     * @return void
     */
    public function testGenuineMessageIsSaved(string $message)
    {
        config(['services.telegram-bot-api.users' => null]);

        $response = $this->post('/callback', $this->validForm(['message' => $message]));

        $response->assertRedirect(self::SUCCESS_URL)->assertSessionHasNoErrors();
        $this->assertDatabaseHas('callbacks', ['phone' => '0000000000']);
    }

    /**
     * @return array
     */
    public function genuineMessages()
    {
        return [
            ['Потрібна кухня 3.5 м, м. Київ, вул. Хрещатик 1.'],
            ['Шафа-купе 2.4х1.8, ДСП/МДФ. Дзвоніть після 18.00'],
            ['Хочу таку ж кухню як на фото. Дякую!'],
        ];
    }

    /**
     * Bots that fill the hidden field get the normal success response, but nothing is saved.
     *
     * @return void
     */
    public function testFilledHoneypotIsNotSaved()
    {
        $response = $this->post('/callback', $this->validForm(['website' => 'http://spam.test']));

        $response->assertRedirect(self::SUCCESS_URL)->assertSessionHas('callback');
        $this->assertDatabaseMissing('callbacks', ['phone' => '0000000000']);
    }

    /**
     * @return void
     */
    public function testFormSentTooFastIsNotSaved()
    {
        $response = $this->post('/callback', $this->validForm(['started_at' => encrypt(now()->timestamp)]));

        $response->assertRedirect(self::SUCCESS_URL)->assertSessionHas('callback');
        $this->assertDatabaseMissing('callbacks', ['phone' => '0000000000']);
    }

    /**
     * Direct posts that never loaded the form have no valid timestamp.
     *
     * @dataProvider invalidTimestamps
     * @return void
     */
    public function testMissingOrForgedTimestampIsNotSaved(?string $startedAt)
    {
        $response = $this->post('/callback', $this->validForm(['started_at' => $startedAt]));

        $response->assertRedirect(self::SUCCESS_URL);
        $this->assertDatabaseMissing('callbacks', ['phone' => '0000000000']);
    }

    /**
     * @return array
     */
    public function invalidTimestamps()
    {
        return [[null], ['1000000000']];
    }

    /**
     * @dataProvider messagesWithLinks
     * @return void
     */
    public function testMessageWithLinkIsRejected(string $message)
    {
        $response = $this->post('/callback', $this->validForm(['message' => $message]));

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseMissing('callbacks', ['phone' => '0000000000']);
    }

    /**
     * @return array
     */
    public function messagesWithLinks()
    {
        return [
            ['Best offer http://spam.example/page'],
            ['Visit https://spam.example'],
            ['Go to www.spam.example now'],
            ['Great prices on cheap-pills.com today'],
            ['SEE SPAM-SITE.XYZ'],
        ];
    }

    /**
     * @param array $overrides
     * @return array
     */
    private function validForm(array $overrides = [])
    {
        return array_merge([
            'name' => 'Test',
            'phone' => '0000000000',
            'message' => 'Test',
            'website' => '',
            'started_at' => encrypt(now()->subSeconds(10)->timestamp),
        ], $overrides);
    }
}
