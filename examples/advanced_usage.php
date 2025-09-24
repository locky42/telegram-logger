<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Locky42\TelegramLogger\TelegramLogger;
use Locky42\TelegramLogger\Config\TelegramConfig;

// Advanced usage with configuration
$config = new TelegramConfig([
    'bot_token' => 'YOUR_BOT_TOKEN',
    'chat_id' => 'YOUR_CHAT_ID',
    'parse_mode' => 'HTML',
    'timeout' => 60
]);

$logger = new TelegramLogger($config);

// Function for logging PHP errors
function telegramErrorHandler($errno, $errstr, $errfile, $errline)
{
    global $logger;
    
    $errorTypes = [
        E_ERROR => 'ERROR',
        E_WARNING => 'WARNING',
        E_PARSE => 'ERROR',
        E_NOTICE => 'INFO',
        E_USER_ERROR => 'ERROR',
        E_USER_WARNING => 'WARNING',
        E_USER_NOTICE => 'INFO'
    ];
    
    $level = $errorTypes[$errno] ?? 'ERROR';
    
    $logger->logCard(
        'PHP Error',
        $errstr,
        $level,
        [
            'File' => $errfile,
            'Line' => $errline,
            'Error type' => $errno
        ]
    );
}

// Set error handler
set_error_handler('telegramErrorHandler');

// Function for logging exceptions
function telegramExceptionHandler($exception)
{
    global $logger;
    
    $logger->logCard(
        'Uncaught Exception',
        $exception->getMessage(),
        'ERROR',
        [
            'File' => $exception->getFile(),
            'Line' => $exception->getLine(),
            'Trace' => $exception->getTraceAsString()
        ]
    );
}

// Set exception handler
set_exception_handler('telegramExceptionHandler');

// Usage example
try {
    $logger->info('Error monitoring system activated');
    
    // Provoke a warning
    echo $undefinedVariable;
    
    // Provoke an error
    throw new Exception('Test exception');
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
