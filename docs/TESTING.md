# Testing

## Testing Overview

This package has a comprehensive test suite that covers all aspects of functionality:

### Test Types

1. **Unit Tests** - test individual components
2. **Component Tests** - test component interactions
3. **Integration Tests** - test full integration
4. **HTTP Tests** - test HTTP functionality with mocking
5. **Functional Tests** - test complete functionality

## Running Tests

### All Tests
```bash
composer test
# or
vendor/bin/phpunit
```

### Individual Test Types
```bash
# Unit tests
composer test-unit

# Component tests  
composer test-component

# Integration tests
composer test-integration

# HTTP tests
composer test-http

# Functional tests
composer test-functional
```

### Code Coverage
```bash
# HTML report
composer test-coverage

# Text report
composer test-coverage-text
```

## Test Structure

```
tests/
├── Unit Tests/
│   ├── TelegramConfigTest.php         # Configuration tests
│   ├── MessageFormatterTest.php       # Formatter tests
│   ├── TelegramLoggerExceptionTest.php # Exception tests
│   ├── LoggerInterfaceTest.php        # Interface tests
│   └── LoggerFactoryTest.php          # Factory tests
├── Component Tests/
│   ├── TelegramLoggerTest.php         # Main logger tests
│   └── AsyncTelegramLoggerTest.php    # Async logger tests
├── Integration/
│   └── IntegrationTest.php            # Integration tests
├── Http/
│   └── HttpTest.php                   # HTTP tests with mocking
└── Functional/
    └── FunctionalTest.php             # Complete functional tests
```

## Test Coverage

### TelegramConfig
- ✅ Creation with correct parameters
- ✅ Required field validation
- ✅ parse_mode validation
- ✅ timeout validation
- ✅ Configuration change methods

### MessageFormatter
- ✅ Basic message formatting
- ✅ Formatting with options
- ✅ Card formatting
- ✅ HTML escaping
- ✅ Markdown escaping
- ✅ Emoji and color management

### TelegramLogger
- ✅ Creation with configuration
- ✅ Creation with token and chat_id
- ✅ All logging methods (info, warning, error, success, debug)
- ✅ logCard method
- ✅ Private formatting methods

### AsyncTelegramLogger
- ✅ Creation and initialization
- ✅ Presence of all interface methods
- ✅ Asynchronous message sending

### LoggerFactory
- ✅ Synchronous logger creation
- ✅ Asynchronous logger creation
- ✅ Creation from configuration
- ✅ Creation from array
- ✅ Creation from JSON file
- ✅ Creation from PHP file
- ✅ File error handling

### LoggerInterface
- ✅ Presence of all methods
- ✅ Correct method signatures
- ✅ Default parameters

### TelegramLoggerException
- ✅ Exception creation
- ✅ Previous exception passing
- ✅ Default values

## HTTP Testing

### Successful Requests
- ✅ Correct URL formation
- ✅ Correct POST data formation
- ✅ Successful response handling (200 OK)

### Error Handling
- ✅ HTTP errors (400, 401, 403, 404, 500)
- ✅ Timeouts
- ✅ cURL errors
- ✅ Telegram API errors

## Integration Tests

- ✅ Complete synchronous logger workflow
- ✅ Complete asynchronous logger workflow
- ✅ Factory integration
- ✅ Runtime configuration changes
- ✅ Formatter integration with logger

## Functional Tests

- ✅ Complete logger lifecycle
- ✅ Creation through all available methods
- ✅ Formatting all message types
- ✅ Managing all configuration parameters
- ✅ Handling all error types

## Mocking and Isolation

Tests use PHPUnit mocking for:
- HTTP request isolation
- Error handling testing
- Method call verification
- Response scenario simulation

## Adding New Tests

1. Create a new test file in appropriate folder
2. Extend `PHPUnit\Framework\TestCase`
3. Add namespace `Locky42\TelegramLogger\Tests\{Category}`
4. Write tests following PHPUnit conventions
5. Run tests to verify

## Best Practices

- Use descriptive test method names
- Test both success and error scenarios
- Use mocking for external dependency isolation
- Check code coverage regularly
- Document complex tests

## Continuous Integration

Tests can be easily integrated into CI/CD pipelines:

```yaml
# GitHub Actions example
- name: Run tests
  run: composer test

- name: Generate coverage
  run: composer test-coverage
```
