<?php

namespace Locky42\TelegramLogger\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Locky42\TelegramLogger\TelegramLogger;
use Locky42\TelegramLogger\AsyncTelegramLogger;
use Locky42\TelegramLogger\Config\TelegramConfig;
use Locky42\TelegramLogger\Factory\LoggerFactory;

/**
 * Integration tests to verify interaction between components
 */
class IntegrationTest extends TestCase
{
    private TelegramConfig $validConfig;

    protected function setUp(): void
    {
        $this->validConfig = new TelegramConfig([
            'bot_token' => 'valid_test_token',
            'chat_id' => 'valid_test_chat_id',
            'parse_mode' => 'HTML',
            'timeout' => 10
        ]);
    }

    public function testFullWorkflowWithSyncLogger()
    {
        $logger = new TelegramLogger($this->validConfig);

        // Check that all methods are called without errors
        $this->assertInstanceOf(TelegramLogger::class, $logger);

        // Test main functionality
        $this->assertTrue(method_exists($logger, 'log'));
        $this->assertTrue(method_exists($logger, 'info'));
        $this->assertTrue(method_exists($logger, 'error'));
    }

    public function testFullWorkflowWithAsyncLogger()
    {
        $logger = new AsyncTelegramLogger($this->validConfig);
        
        $this->assertInstanceOf(AsyncTelegramLogger::class, $logger);

        // Check that all methods are called without errors
        $this->assertTrue(method_exists($logger, 'log'));
        $this->assertTrue(method_exists($logger, 'info'));
        $this->assertTrue(method_exists($logger, 'warning'));
        $this->assertTrue(method_exists($logger, 'error'));
        $this->assertTrue(method_exists($logger, 'success'));
        $this->assertTrue(method_exists($logger, 'debug'));
    }

    public function testFactoryIntegration()
    {
        // Test creation through factory
        $syncLogger = LoggerFactory::createSyncLogger(
            $this->validConfig->getBotToken(),
            $this->validConfig->getChatId()
        );
        
        $asyncLogger = LoggerFactory::createAsyncLogger(
            $this->validConfig->getBotToken(),
            $this->validConfig->getChatId()
        );
        
        $this->assertInstanceOf(TelegramLogger::class, $syncLogger);
        $this->assertInstanceOf(AsyncTelegramLogger::class, $asyncLogger);
    }

    public function testConfigurationChanges()
    {
        $config = new TelegramConfig([
            'bot_token' => 'test_token',
            'chat_id' => 'test_chat_id'
        ]);
        
        // Change configuration
        $config->setChatId('new_chat_id');
        $config->setParseMode('Markdown');
        $config->setTimeout(60);
        
        $this->assertEquals('new_chat_id', $config->getChatId());
        $this->assertEquals('Markdown', $config->getParseMode());
        $this->assertEquals(60, $config->getTimeout());

        // Create logger with updated configuration
        $logger = new TelegramLogger($config);
        $this->assertInstanceOf(TelegramLogger::class, $logger);
    }

    public function testFormatterIntegration()
    {
        $logger = new TelegramLogger($this->validConfig);

        // Test access to formatter via reflection
        $reflection = new \ReflectionClass($logger);
        $formatterProperty = $reflection->getProperty('formatter');
        $formatterProperty->setAccessible(true);
        
        $formatter = $formatterProperty->getValue($logger);
        $this->assertInstanceOf(\Locky42\TelegramLogger\Formatters\MessageFormatter::class, $formatter);

        // Test formatting
        $formatted = $formatter->format('Test message', 'ERROR');
        $this->assertStringContainsString('❌', $formatted);
        $this->assertStringContainsString('ERROR', $formatted);
    }

    public function testErrorHandling()
    {
        // Test with invalid configuration
        $this->expectException(\InvalidArgumentException::class);
        
        new TelegramConfig([
            'bot_token' => '', // Empty token
            'chat_id' => 'test_chat_id'
        ]);
    }

    public function testConfigValidationFlow()
    {
        // Test validation at each step
        $validConfigs = [
            [
                'bot_token' => 'valid_token',
                'chat_id' => 'valid_chat_id',
                'parse_mode' => 'HTML',
                'timeout' => 30
            ],
            [
                'bot_token' => 'another_token',
                'chat_id' => 'another_chat',
                'parse_mode' => 'Markdown',
                'timeout' => 60
            ]
        ];
        
        foreach ($validConfigs as $configData) {
            $config = new TelegramConfig($configData);
            $this->assertEquals($configData['bot_token'], $config->getBotToken());
            $this->assertEquals($configData['chat_id'], $config->getChatId());
            $this->assertEquals($configData['parse_mode'], $config->getParseMode());
            $this->assertEquals($configData['timeout'], $config->getTimeout());
        }
    }

    public function testMultipleLoggersWithSameConfig()
    {
        $config = new TelegramConfig([
            'bot_token' => 'shared_token',
            'chat_id' => 'shared_chat_id'
        ]);
        
        $logger1 = new TelegramLogger($config);
        $logger2 = new AsyncTelegramLogger($config);
        $logger3 = LoggerFactory::createFromConfig($config);
        
        $this->assertInstanceOf(TelegramLogger::class, $logger1);
        $this->assertInstanceOf(AsyncTelegramLogger::class, $logger2);
        $this->assertInstanceOf(TelegramLogger::class, $logger3);
    }
}
