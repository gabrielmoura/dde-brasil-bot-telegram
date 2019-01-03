<?php
/**
 * README
 * This file is intended to set the webhook.
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

    // Set webhook
    $result = $telegram->setWebhook(env('HOOK_URL'));

    // To use a self-signed certificate, use this line instead
    //$result = $telegram->setWebhook($hook_url, ['certificate' => $certificate_path]);

    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (TelegramException $e) {
    echo $e->getMessage();
}