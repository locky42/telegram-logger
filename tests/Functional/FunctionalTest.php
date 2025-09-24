<?php

namespace Locky42\TelegramLogger\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Locky42\TelegramLogger\TelegramLogger;
use Locky42\TelegramLogger\AsyncTelegramLogger;
use Locky42\TelegramLogger\Config\TelegramConfig;
use Locky42\TelegramLogger\Factory\LoggerFactory;
use Locky42\TelegramLogger\Formatters\MessageFormatter;
use Locky42\TelegramLogger\Exceptions\TelegramLoggerException;

/**
 * Functional tests to verify the entire functionality
 * (without real HTTP requests to Telegram API)
 */
class FunctionalTest extends TestCase
{
    private TelegramConfig $config;

    protected function setUp(): void
    {
        $this->config = new TelegramConfig([
            'bot_token' => 'test_bot_token_123456789:ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789',
            'chat_id' => '-1001234567890',
            'parse_mode' => 'HTML',
            'timeout' => 30
        ]);
    }

    /**
     * Test full lifecycle of the synchronous logger
     */
    public function testSyncLoggerFullLifecycle()
    {
        // Creation of the logger
        $logger = new TelegramLogger($this->config);
        $this->assertInstanceOf(TelegramLogger::class, $logger);

        // Testing all logging methods
        $methods = ['info', 'warning', 'error', 'success', 'debug'];
        
        foreach ($methods as $method) {
            $this->assertTrue(method_exists($logger, $method));
        }
        
        // Check that logCard method exists
        $this->assertTrue(method_exists($logger, 'log'));
        $this->assertTrue(method_exists($logger, 'logCard'));
    }

    /**
     * Test full lifecycle of the asynchronous logger
     */
    public function testAsyncLoggerFullLifecycle()
    {
        // Creation of the logger
        $logger = new AsyncTelegramLogger($this->config);
        $this->assertInstanceOf(AsyncTelegramLogger::class, $logger);

        // Testing all logging methods
        $methods = ['log', 'info', 'warning', 'error', 'success', 'debug'];
        
        foreach ($methods as $method) {
            $this->assertTrue(method_exists($logger, $method));
        }
    }

    /**
     * Test factory creation
     */
    public function testFactoryCreation()
    {
        // Synchronous logger
        $syncLogger = LoggerFactory::createSyncLogger(
            $this->config->getBotToken(),
            $this->config->getChatId(),
            ['parse_mode' => 'Markdown']
        );
        
        $this->assertInstanceOf(TelegramLogger::class, $syncLogger);

        // Asynchronous logger
        $asyncLogger = LoggerFactory::createAsyncLogger(
            $this->config->getBotToken(),
            $this->config->getChatId()
        );
        
        $this->assertInstanceOf(AsyncTelegramLogger::class, $asyncLogger);

        // From configuration
        $configLogger = LoggerFactory::createFromConfig($this->config);
        $this->assertInstanceOf(TelegramLogger::class, $configLogger);

        // From array
        $arrayLogger = LoggerFactory::createFromArray([
            'bot_token' => $this->config->getBotToken(),
            'chat_id' => $this->config->getChatId(),
            'parse_mode' => 'HTML'
        ], true);
        
        $this->assertInstanceOf(AsyncTelegramLogger::class, $arrayLogger);
    }

    /**
     * Test message formatting
     */
    public function testMessageFormatting()
    {
        $formatter = new MessageFormatter();

        // Test main formatting
        $formatted = $formatter->format('Test message', 'INFO');
        $this->assertStringContainsString('ℹ️', $formatted);
        $this->assertStringContainsString('[INFO]', $formatted);
        $this->assertStringContainsString('Test message', $formatted);

        // Test card formatting
        $card = $formatter->formatAsCard(
            'Error Title',
            'Error message',
            'ERROR',
            ['User' => 'admin', 'IP' => '127.0.0.1']
        );
        
        $this->assertStringContainsString('Error Title', $card);
        $this->assertStringContainsString('Error message', $card);
        $this->assertStringContainsString('❌', $card);
        $this->assertStringContainsString('User', $card);
        $this->assertStringContainsString('admin', $card);

        // Test escaping
        $html = '<script>alert("test")</script>';
        $escaped = $formatter->escapeHtml($html);
        $this->assertStringNotContainsString('<script>', $escaped);
        $this->assertStringContainsString('&lt;script&gt;', $escaped);
    }

    /**
     * Test configuration management
     */
    public function testConfigurationManagement()
    {
        $config = new TelegramConfig([
            'bot_token' => 'initial_token',
            'chat_id' => 'initial_chat_id'
        ]);

        // Initial values
        $this->assertEquals('initial_token', $config->getBotToken());
        $this->assertEquals('initial_chat_id', $config->getChatId());
        $this->assertEquals('HTML', $config->getParseMode());
        $this->assertEquals(30, $config->getTimeout());

        // Change values
        $config->setChatId('new_chat_id');
        $config->setParseMode('Markdown');
        $config->setTimeout(60);

        $this->assertEquals('new_chat_id', $config->getChatId());
        $this->assertEquals('Markdown', $config->getParseMode());
        $this->assertEquals(60, $config->getTimeout());
    }

    /**
     * Test configuration error handling
     */
    public function testConfigurationErrorHandling()
    {
        // Empty token
        $this->expectException(\InvalidArgumentException::class);
        new TelegramConfig(['bot_token' => '', 'chat_id' => 'test']);
    }

    /**
     * Test invalid parse_mode handling
     */
    public function testInvalidParseModeHandling()
    {
        $config = new TelegramConfig([
            'bot_token' => 'test_token',
            'chat_id' => 'test_chat_id'
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $config->setParseMode('InvalidMode');
    }

    /**
     * Test invalid timeout handling
     */
    public function testInvalidTimeoutHandling()
    {
        $config = new TelegramConfig([
            'bot_token' => 'test_token',
            'chat_id' => 'test_chat_id'
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $config->setTimeout(0);
    }

    /**
     * Test creation with different parameters
     */
    public function testCreationWithDifferentParameters()
    {
        $configs = [
            ['parse_mode' => 'HTML', 'timeout' => 30],
            ['parse_mode' => 'Markdown', 'timeout' => 60],
            ['parse_mode' => 'MarkdownV2', 'timeout' => 120]
        ];

        foreach ($configs as $configParams) {
            $config = new TelegramConfig(array_merge([
                'bot_token' => 'test_token',
                'chat_id' => 'test_chat_id'
            ], $configParams));

            $logger = new TelegramLogger($config);
            $this->assertInstanceOf(TelegramLogger::class, $logger);
        }
    }

    /**
     * Test all emoji levels
     */
    public function testAllEmojiLevels()
    {
        $formatter = new MessageFormatter();
        
        $levels = ['INFO', 'WARNING', 'ERROR', 'SUCCESS', 'DEBUG'];
        $emojis = ['ℹ️', '⚠️', '❌', '✅', '🐛'];
        
        for ($i = 0; $i < count($levels); $i++) {
            $emoji = $formatter->getLevelEmoji($levels[$i]);
            $this->assertEquals($emojis[$i], $emoji);
        }
    }

    /**
     * Test all color levels
     */
    public function testAllColorLevels()
    {
        $formatter = new MessageFormatter();
        
        $levels = ['INFO', 'WARNING', 'ERROR', 'SUCCESS', 'DEBUG'];
        $colors = ['#2196F3', '#FF9800', '#F44336', '#4CAF50', '#9C27B0'];
        
        for ($i = 0; $i < count($levels); $i++) {
            $color = $formatter->getLevelColor($levels[$i]);
            $this->assertEquals($colors[$i], $color);
        }
    }

    /**
     * Test exception handling
     */
    public function testExceptionHandling()
    {
        $exception = new TelegramLoggerException('Test error', 100);
        
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertEquals('Test error', $exception->getMessage());
        $this->assertEquals(100, $exception->getCode());
    }
}
