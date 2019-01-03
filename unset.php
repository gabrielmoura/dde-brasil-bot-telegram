<?php
/**
 * README
 * This file is intended to unset the webhook.
 * Uncommented parameters must be filled
 */

// Load composer
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Longman\TelegramBot\{Exception\TelegramException, Telegram};

try {
    Dotenv::create(__DIR__)->load();
    // Create Telegram API object
    $telegram = new Telegram(env('API_KEY'), env('USERNAME'));

    // Delete webhook
    $result = $telegram->deleteWebhook();

    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (TelegramException $e) {
    echo $e->getMessage();
}
