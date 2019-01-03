<?php
/**
 * README
 * This file is intended to set the webhook.
 * Uncommented parameters must be filled
 */

// Load composer
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

$dotenv = \Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram(getenv('API_KEY'), getenv('USERNAME'));

    // Set webhook
    $result = $telegram->setWebhook(getenv('HOOK_URL'));

    // To use a self-signed certificate, use this line instead
    //$result = $telegram->setWebhook($hook_url, ['certificate' => $certificate_path]);

    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}
