<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Locky42\TelegramLogger\AsyncTelegramLogger;

// Asynchronous logging (faster, but without delivery verification)
$asyncLogger = new AsyncTelegramLogger('YOUR_BOT_TOKEN', 'YOUR_CHAT_ID');

try {
    echo "Sending messages asynchronously...\n";
    
    // Send several messages in a row without waiting
    for ($i = 1; $i <= 5; $i++) {
        $asyncLogger->info("Asynchronous message #{$i}");
        echo "Message #{$i} sent\n";
    }
    
    echo "All messages sent instantly!\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
