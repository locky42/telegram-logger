<?php

namespace Locky42\TelegramLogger\Tests;

use PHPUnit\Framework\TestCase;
use Locky42\TelegramLogger\AsyncTelegramLogger;
use Locky42\TelegramLogger\Config\TelegramConfig;

class AsyncTelegramLoggerTest extends TestCase
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
        $logger = new AsyncTelegramLogger($this->config);
        $this->assertInstanceOf(AsyncTelegramLogger::class, $logger);
    }

    public function testConstructorWithTokenAndChatId()
    {
        $logger = new AsyncTelegramLogger('test_token', 'test_chat_id');
        $this->assertInstanceOf(AsyncTelegramLogger::class, $logger);
    }

    public function testLoggerImplementsInterface()
    {
        $logger = new AsyncTelegramLogger($this->config);
        $this->assertInstanceOf(\Locky42\TelegramLogger\LoggerInterface::class, $logger);
    }

    /**
     * Test that log method calls the private sendMessageAsync method
     */
    public function testLogMethodCallsPrivateMethod()
    {
        $logger = $this->getMockBuilder(AsyncTelegramLogger::class)
            ->setConstructorArgs([$this->config])
            ->onlyMethods(['sendMessageAsync'])
            ->getMock();

        $logger->expects($this->once())
            ->method('sendMessageAsync')
            ->willReturn(true);

        // Use reflection to call the private log method
        $reflection = new \ReflectionClass($logger);
        $logMethod = $reflection->getMethod('log');
        $logMethod->setAccessible(true);
        
        $result = $logMethod->invoke($logger, 'Test message', 'INFO');
        $this->assertTrue($result);
    }

    public function testAsyncLoggerHasAllRequiredMethods()
    {
        $logger = new AsyncTelegramLogger($this->config);
        
        $this->assertTrue(method_exists($logger, 'log'));
        $this->assertTrue(method_exists($logger, 'info'));
        $this->assertTrue(method_exists($logger, 'warning'));
        $this->assertTrue(method_exists($logger, 'error'));
        $this->assertTrue(method_exists($logger, 'success'));
        $this->assertTrue(method_exists($logger, 'debug'));
    }
}
