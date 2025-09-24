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
$logger = new TelegramLogger('YOUR_BOT_TOKEN', 'YOUR_CHAT_ID');

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
    'timeout' => 30
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

## License

MIT License
