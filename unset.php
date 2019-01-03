<?php
/**
 * README
 * This file is intended to unset the webhook.
 * Uncommented parameters must be filled
 */

// Load composer
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';
$dotenv = (new Dotenv\Dotenv(__DIR__))->load();

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram(getenv('API_KEY'), getenv('USERNAME'));

    // Delete webhook
    $result = $telegram->deleteWebhook();

    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}
