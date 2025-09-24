# API Documentation

## TelegramLogger

Main class for synchronous logging to Telegram.

### Constructor

```php
public function __construct($botTokenOrConfig, ?string $chatId = null)
```

**Parameters:**
- `$botTokenOrConfig` - bot token (string) or TelegramConfig object
- `$chatId` - chat ID (optional if using config)

### Logging Methods

#### log(string $message, string $level = 'INFO', array $options = []): bool

Main logging method.

**Parameters:**
- `$message` - message text
- `$level` - log level (INFO, WARNING, ERROR, SUCCESS, DEBUG)
- `$options` - additional formatting options

**Options:**
- `include_timestamp` - include timestamp (bool, default: true)
- `include_emoji` - include emoji (bool, default: true)
- `include_level` - include level (bool, default: true)

#### info(string $message): bool
#### warning(string $message): bool
#### error(string $message): bool
#### success(string $message): bool
#### debug(string $message): bool

Shorthand methods for different log levels.

#### logCard(string $title, string $message, string $level = 'INFO', array $fields = []): bool

Sends message in card format.

**Parameters:**
- `$title` - card title
- `$message` - message text
- `$level` - log level
- `$fields` - additional fields (associative array)

## AsyncTelegramLogger

Class for asynchronous logging to Telegram (without waiting for response).

### Methods

Similar to TelegramLogger, but without `logCard` method.

## TelegramConfig

Configuration class.

### Constructor

```php
public function __construct(array $config)
```

**Configuration parameters:**
- `bot_token` - bot token (required)
- `chat_id` - chat ID (required)
- `parse_mode` - parsing mode (HTML, Markdown, MarkdownV2, default: HTML)
- `timeout` - request timeout in seconds (default: 30)

### Methods

- `getBotToken(): string`
- `getChatId(): string`
- `getParseMode(): string`
- `getTimeout(): int`
- `setChatId(string $chatId): void`
- `setParseMode(string $parseMode): void`
- `setTimeout(int $timeout): void`

## MessageFormatter

Class for message formatting.

### Methods

#### format(string $message, string $level, array $options = []): string

Formats standard message.

#### formatAsCard(string $title, string $message, string $level, array $fields = []): string

Formats message as card.

#### escapeHtml(string $text): string

Escapes HTML characters.

#### escapeMarkdown(string $text): string

Escapes Markdown characters.

#### getLevelEmoji(string $level): string

Returns emoji for log level.

#### setLevelEmoji(string $level, string $emoji): void

Sets emoji for level.

## LoggerFactory

Factory for creating loggers.

### Static Methods

#### createSyncLogger(string $botToken, string $chatId, array $options = []): LoggerInterface

Creates synchronous logger.

#### createAsyncLogger(string $botToken, string $chatId, array $options = []): LoggerInterface

Creates asynchronous logger.

#### createFromConfig(TelegramConfig $config, bool $async = false): LoggerInterface

Creates logger from configuration object.

#### createFromArray(array $config, bool $async = false): LoggerInterface

Creates logger from configuration array.

#### createFromFile(string $configPath, bool $async = false): LoggerInterface

Creates logger from configuration file (supports .json and .php files).

## Exceptions

### TelegramLoggerException

Main package exception, extends standard Exception.

## Usage Examples

See `examples/` folder for detailed examples of all package features.
