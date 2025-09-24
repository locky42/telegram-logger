<?php

namespace Locky42\TelegramLogger\Tests;

use PHPUnit\Framework\TestCase;
use Locky42\TelegramLogger\Factory\LoggerFactory;
use Locky42\TelegramLogger\Config\TelegramConfig;
use Locky42\TelegramLogger\TelegramLogger;
use Locky42\TelegramLogger\AsyncTelegramLogger;
use Locky42\TelegramLogger\LoggerInterface;

class LoggerFactoryTest extends TestCase
{
    public function testCreateSyncLogger()
    {
        $logger = LoggerFactory::createSyncLogger('test_token', 'test_chat_id');
        
        $this->assertInstanceOf(TelegramLogger::class, $logger);
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }

    public function testCreateAsyncLogger()
    {
        $logger = LoggerFactory::createAsyncLogger('test_token', 'test_chat_id');
        
        $this->assertInstanceOf(AsyncTelegramLogger::class, $logger);
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }

    public function testCreateSyncLoggerWithOptions()
    {
        $options = [
            'parse_mode' => 'Markdown',
            'timeout' => 60
        ];
        
        $logger = LoggerFactory::createSyncLogger('test_token', 'test_chat_id', $options);
        
        $this->assertInstanceOf(TelegramLogger::class, $logger);
    }

    public function testCreateFromConfig()
    {
        $config = new TelegramConfig([
            'bot_token' => 'test_token',
            'chat_id' => 'test_chat_id'
        ]);
        
        $syncLogger = LoggerFactory::createFromConfig($config, false);
        $asyncLogger = LoggerFactory::createFromConfig($config, true);
        
        $this->assertInstanceOf(TelegramLogger::class, $syncLogger);
        $this->assertInstanceOf(AsyncTelegramLogger::class, $asyncLogger);
    }

    public function testCreateFromArray()
    {
        $config = [
            'bot_token' => 'test_token',
            'chat_id' => 'test_chat_id',
            'parse_mode' => 'HTML'
        ];
        
        $syncLogger = LoggerFactory::createFromArray($config, false);
        $asyncLogger = LoggerFactory::createFromArray($config, true);
        
        $this->assertInstanceOf(TelegramLogger::class, $syncLogger);
        $this->assertInstanceOf(AsyncTelegramLogger::class, $asyncLogger);
    }

    public function testCreateFromJsonFile()
    {
        $configPath = __DIR__ . '/fixtures/test_config.json';
        $configData = [
            'bot_token' => 'test_token',
            'chat_id' => 'test_chat_id',
            'parse_mode' => 'HTML'
        ];
        
        // Create a temporary config file
        if (!is_dir(__DIR__ . '/fixtures')) {
            mkdir(__DIR__ . '/fixtures', 0755, true);
        }
        file_put_contents($configPath, json_encode($configData));
        
        $logger = LoggerFactory::createFromFile($configPath);
        
        $this->assertInstanceOf(TelegramLogger::class, $logger);

        // Remove temporary file
        unlink($configPath);
        rmdir(__DIR__ . '/fixtures');
    }

    public function testCreateFromPhpFile()
    {
        $configPath = __DIR__ . '/fixtures/test_config.php';
        $configData = "<?php\nreturn [\n    'bot_token' => 'test_token',\n    'chat_id' => 'test_chat_id'\n];";

        // Create temporary config file
        if (!is_dir(__DIR__ . '/fixtures')) {
            mkdir(__DIR__ . '/fixtures', 0755, true);
        }
        file_put_contents($configPath, $configData);
        
        $logger = LoggerFactory::createFromFile($configPath, true); // async
        
        $this->assertInstanceOf(AsyncTelegramLogger::class, $logger);

        // Remove temporary file
        unlink($configPath);
        rmdir(__DIR__ . '/fixtures');
    }

    public function testCreateFromFileWithNonExistentFile()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Configuration file not found');
        
        LoggerFactory::createFromFile('/non/existent/file.json');
    }

    public function testCreateFromFileWithUnsupportedFormat()
    {
        $configPath = __DIR__ . '/fixtures/test_config.txt';

        // Create temporary file with unsupported format
        if (!is_dir(__DIR__ . '/fixtures')) {
            mkdir(__DIR__ . '/fixtures', 0755, true);
        }
        file_put_contents($configPath, 'invalid config');
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported configuration file format');
        
        try {
            LoggerFactory::createFromFile($configPath);
        } finally {
            unlink($configPath);
            rmdir(__DIR__ . '/fixtures');
        }
    }

    public function testCreateFromFileWithInvalidJson()
    {
        $configPath = __DIR__ . '/fixtures/invalid.json';

        // Create temporary file with invalid JSON
        if (!is_dir(__DIR__ . '/fixtures')) {
            mkdir(__DIR__ . '/fixtures', 0755, true);
        }
        file_put_contents($configPath, '{invalid json}');
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid configuration format');
        
        try {
            LoggerFactory::createFromFile($configPath);
        } finally {
            unlink($configPath);
            rmdir(__DIR__ . '/fixtures');
        }
    }

    public function testCreateFromFileWithInvalidPhpConfig()
    {
        $configPath = __DIR__ . '/fixtures/invalid.php';

        // Create temporary file with invalid PHP config
        if (!is_dir(__DIR__ . '/fixtures')) {
            mkdir(__DIR__ . '/fixtures', 0755, true);
        }
        file_put_contents($configPath, "<?php\nreturn 'invalid config';");
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid configuration format');
        
        try {
            LoggerFactory::createFromFile($configPath);
        } finally {
            unlink($configPath);
            rmdir(__DIR__ . '/fixtures');
        }
    }
}
