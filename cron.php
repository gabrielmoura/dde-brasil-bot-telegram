<?php
/**
 * README
 * This configuration file is intended to run a list of commands with crontab.
 * Uncommented parameters must be filled
 */

// Your command(s) to run, pass it just like in a message (arguments supported)
$commands = [
    '/whoami',
    "/echo I'm a bot!",
];

// Load composer
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Longman\TelegramBot\{Exception\TelegramException, Exception\TelegramLogException, Telegram, TelegramLog};


try {
    Dotenv::create(__DIR__)->load();
    // Create Telegram API object
    $telegram = new Telegram(env('API_KEY'), env('USERNAME'));

    // Add commands paths containing your custom commands
    $telegram->addCommandsPaths([__DIR__ . '/Commands/']);

    // Enable admin users
    $telegram->enableAdmins(['650501601']);

    // Enable MySQL
    $telegram->enableMySql([
        'host' => env('DB_HOST'),
        'user' => env('DB_USER'),
        'password' => env('DB_PASSWORD'),
        'database' => env('DB_DATABASE'),
    ]);

    // Logging (Error, Debug and Raw Updates)
    TelegramLog::initErrorLog(__DIR__ . '/' . env('USERNAME') . "_error.log");
    TelegramLog::initDebugLog(__DIR__ . '/' . env('USERNAME') . "_debug.log");
    TelegramLog::initUpdateLog(__DIR__ . '/' . env('USERNAME') . "_update.log");


    // If you are using a custom Monolog instance for logging, use this instead of the above
    //Longman\TelegramBot\TelegramLog::initialize($your_external_monolog_instance);

    // Set custom Upload and Download paths
    $telegram->setDownloadPath(env('PATH_DOWNLOAD', __DIR__ . '/assets/download'));
    $telegram->setUploadPath(env('PATH_UPLOAD', __DIR__ . '/assets/upload'));


    // Here you can set some command specific parameters,
    // e.g. Google geocode/timezone api key for /date command:
    //$telegram->setCommandConfig('date', ['google_api_key' => 'your_google_api_key_here']);

    // Botan.io integration
    //$telegram->enableBotan('your_botan_token');

    // Requests Limiter (tries to prevent reaching Telegram API limits)
    $telegram->enableLimiter();

    // Run user selected commands
    $telegram->runCommands($commands);

} catch (TelegramException $e) {
    // Silence is golden!
    //echo $e;
    // Log telegram errors
    TelegramLog::error($e);
} catch (TelegramLogException $e) {
    // Silence is golden!
    // Uncomment this to catch log initialisation errors
    //echo $e;
    TelegramLog::error($e);
}
