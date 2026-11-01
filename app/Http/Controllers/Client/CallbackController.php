<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\CallbackRequest;
use App\Models\Callback;
use App\Notifications\CallbackMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CallbackController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CallbackRequest $request)
    {
        if ($request->isSpam()) {
            Log::warning('Callback rejected as spam');

            return $this->success();
        }

        $record = new Callback();
        $record->name = $request->get('name');
        $record->phone = $request->get('phone');
        $record->message = $request->get('message');
        $record->save();

        foreach (Callback::getUsersForNotifications() as $chatId) {
            $record->telegramChatId = $chatId;

            try {
                $record->notify(new CallbackMessage());
            } catch (Throwable $e) {
                report($e);
            }
        }

        return $this->success();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    private function success()
    {
        return redirect('/#contact-form')->with('callback', 'Вашу заявку збережено!');
    }
}
