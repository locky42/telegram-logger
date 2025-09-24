# Development

## Development Setup

```bash
git clone https://github.com/locky42/telegram-logger.git
cd telegram-logger
composer install
```

## Running Tests

```bash
composer test
# or
vendor/bin/phpunit
```

## Project Structure

```
telegram-logger/
├── src/                          # Source code
│   ├── Config/                   # Configuration
│   │   └── TelegramConfig.php
│   ├── Exceptions/               # Exceptions
│   │   └── TelegramLoggerException.php
│   ├── Factory/                  # Factories
│   │   └── LoggerFactory.php
│   ├── Formatters/               # Formatters
│   │   └── MessageFormatter.php
│   ├── AsyncTelegramLogger.php   # Async logger
│   ├── LoggerInterface.php       # Interface
│   └── TelegramLogger.php        # Main logger
├── tests/                        # Tests
│   └── TelegramConfigTest.php
├── examples/                     # Usage examples
│   ├── config/                   # Config files
│   ├── advanced_usage.php
│   ├── async_usage.php
│   ├── basic_usage.php
│   └── factory_usage.php
├── docs/                         # Documentation
│   └── API.md
├── composer.json                 # Composer config
├── phpunit.xml                   # PHPUnit config
├── README.md                     # Main docs
├── CHANGELOG.md                  # Changelog
└── LICENSE                       # License
```

## Adding New Features

### 1. New Log Level

```php
// In MessageFormatter.php
private array $levelEmojis = [
    // ... existing
    'CUSTOM' => '🔧'
];

// In LoggerInterface.php and implementations
public function custom(string $message): bool
{
    return $this->log($message, 'CUSTOM');
}
```

### 2. New Formatter

```php
namespace Locky42\TelegramLogger\Formatters;

class CustomFormatter
{
    public function format(string $message, string $level): string
    {
        // Your formatting logic
        return $formattedMessage;
    }
}
```

### 3. New Transport

```php
namespace Locky42\TelegramLogger\Transports;

class CustomTransport
{
    public function send(string $message, array $config): bool
    {
        // Your sending logic
        return true;
    }
}
```

## Coding Standards

- Use PSR-4 for autoloading
- Follow PSR-12 for code style
- Document all public methods
- Write tests for new functionality
- Use PHP 7.4+ type declarations

## Contributing

1. Fork the repository
2. Create a feature branch
3. Add tests
4. Ensure all tests pass
5. Update documentation
6. Create a pull request

## Debugging

For debugging you can use:

```php
// Enable errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// File logging
$logger->log('Debug message', 'DEBUG');
```

## Requirements

- PHP >= 7.4
- cURL extension
- JSON extension
- Telegram bot token
- Telegram chat ID
