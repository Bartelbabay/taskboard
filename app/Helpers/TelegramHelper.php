<?php

namespace App\Helpers;

class TelegramHelper
{
    public function sendTelegramNotification($task, $action='Created') {
        $telegramChatId = env('TELEGRAM_CHAT_ID', '-4603327708');
        $telegramToken = env('TELEGRAM_BOT_TOKEN', '7551414099:AAFDb9MoQ4BKDg8mB9H8KbXJ3yCMh-m47f0');
        $message = $action . ': ' . $task->title;

        $url = "https://api.telegram.org/bot$telegramToken/sendMessage?chat_id=$telegramChatId&text=" . urlencode($message);

        file_get_contents($url);
    }
}
