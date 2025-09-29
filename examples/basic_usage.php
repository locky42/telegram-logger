<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Locky42\TelegramLogger\TelegramLogger;

// Basic usage
$botToken = '8256677096:AAGaIGto_l9fuARk6LUDcUodb0ks6iZkZD8';
$chatId = 'YOUR_CHAT_ID';
$threadId = 5;

// Simple way
$logger = new TelegramLogger($chatId, $botToken, $threadId);

try {
    // Different log levels
    $logger->info('Application started successfully');
    $logger->warning('Low memory level');
    $logger->error('Database connection error');
    $logger->success('Transaction completed successfully');
    $logger->debug('Variable value: ' . json_encode(['user' => 123]));

    // Usage with options
    $logger->log('Test message', 'INFO', [
        'include_timestamp' => false,
        'include_emoji' => true
    ]);

    // Logging as a card
    $logger->logCard(
        'New error',
        'A critical system error has occurred',
        'ERROR',
        [
            'User' => 'admin@example.com',
            'IP address' => '192.168.1.100',
            'Time' => date('Y-m-d H:i:s')
        ]
    );

} catch (\Exception $e) {
    echo "Logging error: " . $e->getMessage() . "\n";
}
