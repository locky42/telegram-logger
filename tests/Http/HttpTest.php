<?php

namespace Locky42\TelegramLogger\Tests\Http;

use PHPUnit\Framework\TestCase;
use Locky42\TelegramLogger\TelegramLogger;
use Locky42\TelegramLogger\Config\TelegramConfig;
use Locky42\TelegramLogger\Exceptions\TelegramLoggerException;

/**
 * Tests HTTP functionality (simplified without complex mocking)
 */
class HttpTest extends TestCase
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
     * Test logger creation with different tokens
     */
    public function testLoggerCreationWithDifferentTokens()
    {
        $tokens = [
            'bot123456789:ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'bot987654321:ZYXWVUTSRQPONMLKJIHGFEDCBA',
            'test_token_123'
        ];

        foreach ($tokens as $token) {
            $config = new TelegramConfig([
                'bot_token' => $token,
                'chat_id' => 'test_chat_id'
            ]);
            
            $logger = new TelegramLogger($config);
            $this->assertInstanceOf(TelegramLogger::class, $logger);
        }
    }

    /**
     * Test timeout configuration
     */
    public function testTimeoutConfiguration()
    {
        $timeouts = [10, 30, 60, 120];

        foreach ($timeouts as $timeout) {
            $config = new TelegramConfig([
                'bot_token' => 'test_token',
                'chat_id' => 'test_chat_id',
                'timeout' => $timeout
            ]);

            $this->assertEquals($timeout, $config->getTimeout());
            
            $logger = new TelegramLogger($config);
            $this->assertInstanceOf(TelegramLogger::class, $logger);
        }
    }

    /**
     * Test different parse_mode
     */
    public function testParseModeConfiguration()
    {
        $parseModes = ['HTML', 'Markdown', 'MarkdownV2'];

        foreach ($parseModes as $parseMode) {
            $config = new TelegramConfig([
                'bot_token' => 'test_token',
                'chat_id' => 'test_chat_id',
                'parse_mode' => $parseMode
            ]);

            $this->assertEquals($parseMode, $config->getParseMode());
            
            $logger = new TelegramLogger($config);
            $this->assertInstanceOf(TelegramLogger::class, $logger);
        }
    }

    /**
     * Test exception creation
     */
    public function testTelegramLoggerExceptionCreation()
    {
        $exception = new TelegramLoggerException('HTTP Error: 400 Bad Request', 400);
        
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertEquals('HTTP Error: 400 Bad Request', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
    }

    /**
     * Test logger has HTTP related properties
     */
    public function testLoggerHasHttpRelatedProperties()
    {
        $logger = new TelegramLogger($this->config);
        
        $reflection = new \ReflectionClass($logger);

        // Check for property existence
        $this->assertTrue($reflection->hasProperty('config'));
        $this->assertTrue($reflection->hasProperty('apiUrl'));
        $this->assertTrue($reflection->hasProperty('formatter'));

        // Check for method existence
        $this->assertTrue($reflection->hasMethod('sendMessage'));
        $this->assertTrue($reflection->hasMethod('log'));
    }

    /**
     * Test URL formation for different tokens
     */
    public function testUrlFormationForDifferentTokens()
    {
        $tokens = [
            'bot123:ABC',
            'bot456789:DEFGHIJKLMNOP',
            'bot999:XYZ123'
        ];

        foreach ($tokens as $token) {
            $config = new TelegramConfig([
                'bot_token' => $token,
                'chat_id' => 'test_chat_id'
            ]);
            
            $logger = new TelegramLogger($config);
            
            $reflection = new \ReflectionClass($logger);
            $apiUrlProperty = $reflection->getProperty('apiUrl');
            $apiUrlProperty->setAccessible(true);
            
            $expectedUrl = "https://api.telegram.org/bot{$token}/sendMessage";
            $actualUrl = $apiUrlProperty->getValue($logger);
            
            $this->assertEquals($expectedUrl, $actualUrl);
        }
    }
}
