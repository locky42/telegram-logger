# Changelog

All notable changes to this project will be documented in this file.

## [1.0.0] - 2025-09-24

### Added
- Main `TelegramLogger` class for synchronous logging
- `AsyncTelegramLogger` class for asynchronous logging
- `TelegramConfig` class for configuration management
- `MessageFormatter` class for message formatting
- `LoggerInterface` for API standardization
- `LoggerFactory` factory for creating loggers
- Support for different log levels (INFO, WARNING, ERROR, SUCCESS, DEBUG)
- Message formatting as cards with additional information
- HTML and Markdown formatting support
- Ability to configure emojis for each level
- `TelegramLoggerException` exceptions for error handling
- PSR-4 autoloading
- Usage examples
- API documentation
- PHPUnit tests
- JSON and PHP configuration files

### Features
- Synchronous and asynchronous message sending
- Request timeout configuration
- Support for different chat IDs
- Special character escaping
- Configuration validation
- Telegram API error handling

### Examples
- Basic usage
- Advanced usage with PHP error handling
- Asynchronous logging
- Using logger factory
- File-based configuration
