<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Locky42\TelegramLogger\Factory\LoggerFactory;

try {
    // Creation via factory
    echo "Creating loggers via factory...\n";
    
    // Synchronous logger
    $syncLogger = LoggerFactory::createSyncLogger('YOUR_BOT_TOKEN', 'YOUR_CHAT_ID', [
        'parse_mode' => 'HTML',
        'timeout' => 60
    ]);
    
    // Asynchronous logger
    $asyncLogger = LoggerFactory::createAsyncLogger('YOUR_BOT_TOKEN', 'YOUR_CHAT_ID');
    
    // From JSON file
    $jsonLogger = LoggerFactory::createFromFile(__DIR__ . '/config/telegram.json');
    
    // From PHP file
    $phpLogger = LoggerFactory::createFromFile(__DIR__ . '/config/telegram.php', true); // async
    
    // Test loggers
    $syncLogger->info('Message from synchronous logger');
    $asyncLogger->warning('Message from asynchronous logger');
    $jsonLogger->success('Message from JSON logger');
    $phpLogger->error('Message from PHP logger');
    
    echo "All loggers tested!\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
