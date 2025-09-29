<?php

namespace Locky42\TelegramLogger\Tests;

use PHPUnit\Framework\TestCase;
use Locky42\TelegramLogger\TelegramLogger;
use Locky42\TelegramLogger\Config\TelegramConfig;

class TelegramLoggerTest extends TestCase
{
    private TelegramConfig $config;

    protected function setUp(): void
    {
        $this->config = new TelegramConfig([
            'bot_token' => 'test_bot_token',
            'chat_id' => 'test_chat_id',
            'parse_mode' => 'HTML',
            'timeout' => 30
        ]);
    }

    public function testConstructorWithConfig()
    {
        $logger = new TelegramLogger($this->config);
        $this->assertInstanceOf(TelegramLogger::class, $logger);
    }

    public function testConstructorWithTokenAndChatId()
    {
        $logger = new TelegramLogger('test_chat_id', 'test_token');
        $this->assertInstanceOf(TelegramLogger::class, $logger);
    }

    public function testLoggerImplementsInterface()
    {
        $logger = new TelegramLogger($this->config);
        $this->assertInstanceOf(\Locky42\TelegramLogger\LoggerInterface::class, $logger);
    }

    /**
     * Test that logging methods exist
     */
    public function testLoggingMethodsExist()
    {
        $logger = new TelegramLogger($this->config);
        
        $this->assertTrue(method_exists($logger, 'log'));
        $this->assertTrue(method_exists($logger, 'info'));
        $this->assertTrue(method_exists($logger, 'warning'));
        $this->assertTrue(method_exists($logger, 'error'));
        $this->assertTrue(method_exists($logger, 'success'));
        $this->assertTrue(method_exists($logger, 'debug'));
        $this->assertTrue(method_exists($logger, 'logCard'));
    }

    /**
     * Test creating logger with different configurations
     */
    public function testLoggerWithDifferentConfigs()
    {
        $configs = [
            ['bot_token' => 'token1', 'chat_id' => 'chat1', 'parse_mode' => 'HTML'],
            ['bot_token' => 'token2', 'chat_id' => 'chat2', 'parse_mode' => 'Markdown'],
            ['bot_token' => 'token3', 'chat_id' => 'chat3', 'timeout' => 60]
        ];

        foreach ($configs as $configData) {
            $config = new TelegramConfig($configData);
            $logger = new TelegramLogger($config);
            $this->assertInstanceOf(TelegramLogger::class, $logger);
        }
    }

    /**
     * Test API URL formation
     */
    public function testApiUrlFormation()
    {
        $logger = new TelegramLogger($this->config);
        
        $reflection = new \ReflectionClass($logger);
        $apiUrlProperty = $reflection->getProperty('apiUrl');
        $apiUrlProperty->setAccessible(true);
        
        $expectedUrl = 'https://api.telegram.org/bot' . $this->config->getBotToken() . '/sendMessage';
        $actualUrl = $apiUrlProperty->getValue($logger);
        
        $this->assertEquals($expectedUrl, $actualUrl);
    }

    /**
     * Test logger properties
     */
    public function testLoggerProperties()
    {
        $logger = new TelegramLogger($this->config);
        
        $reflection = new \ReflectionClass($logger);

        // Check for the presence of required properties
        $this->assertTrue($reflection->hasProperty('config'));
        $this->assertTrue($reflection->hasProperty('formatter'));
        $this->assertTrue($reflection->hasProperty('apiUrl'));

        // Check for the presence of the sendMessage method
        $this->assertTrue($reflection->hasMethod('sendMessage'));
    }

    /**
     * Test that the logger implements the required interface
     */
    public function testImplementsLoggerInterface()
    {
        $logger = new TelegramLogger($this->config);
        $interfaces = class_implements($logger);
        
        $this->assertContains('Locky42\TelegramLogger\LoggerInterface', $interfaces);
    }
}
