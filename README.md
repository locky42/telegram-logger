# Telegram Logger

Simple and reliable service for logging messages to Telegram with PSR-4 autoloading support.

## Installation

```bash
composer require locky42/telegram-logger
```

## Usage

### Basic Usage

```php
<?php

use Locky42\TelegramLogger\TelegramLogger;

// Initialize the logger
$logger = new TelegramLogger('YOUR_CHAT_ID', 'YOUR_BOT_TOKEN');

// Send a message
$logger->log('Test message');

// Send a message with log level
$logger->info('Information message');
$logger->warning('Warning');
$logger->error('Error');
```

### Configuration

```php
<?php

use Locky42\TelegramLogger\TelegramLogger;
use Locky42\TelegramLogger\Config\TelegramConfig;

$config = new TelegramConfig([
    'bot_token' => 'YOUR_BOT_TOKEN',
    'chat_id' => 'YOUR_CHAT_ID',
    'parse_mode' => 'HTML', // or 'Markdown'
    'timeout' => 30,
    'thread_id' => 123456789 // optional: message thread ID for group discussions
]);

$logger = new TelegramLogger($config);

```

## Getting Bot Token

1. Message [@BotFather](https://t.me/botfather) in Telegram
2. Use the `/newbot` command
3. Give your bot a name
4. Get the bot token



## Getting Chat ID

1. Add the bot to the chat
2. Send any message to the chat
3. Go to: `https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getUpdates`
4. Find `chat.id` in the response

## Getting Thread ID

For group discussions with threads:

1. Enable topics in your group (Group Settings > Topics)
2. Create a new topic/thread in the group
3. Send a message in that thread
4. Go to: `https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getUpdates`
5. Find `message_thread_id` in the response

## Testing

```bash
composer test
```

## License

MIT License
