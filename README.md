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

// Initialize the logger (default bot token is used if not provided)
$logger = new TelegramLogger('YOUR_CHAT_ID');
// or
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

// You can omit 'bot_token' to use the default public bot:
// 'bot_token' => '8256677096:AAGaIGto_l9fuARk6LUDcUodb0ks6iZkZD8',
```

## Getting Bot Token

1. Message [@BotFather](https://t.me/botfather) in Telegram
2. Use the `/newbot` command
3. Give your bot a name
4. Get the bot token

### Default Bot Token

If you do not provide your own bot token, the default public bot will be used:

```
8256677096:AAGaIGto_l9fuARk6LUDcUodb0ks6iZkZD8
```

You can use this for quick tests or demos, but for production use, create your own bot for privacy and security.

### Default Bot

By default, the package uses the public bot [@tg_multilogger_bot](https://t.me/tg_multilogger_bot).

You can use this bot for quick tests or demos. For production and privacy, it is recommended to create your own bot via [@BotFather](https://t.me/botfather).

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

## License

MIT License
