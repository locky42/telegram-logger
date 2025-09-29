<?php

namespace Locky42\TelegramLogger\Tests;

use PHPUnit\Framework\TestCase;
use Locky42\TelegramLogger\Config\TelegramConfig;

class TelegramConfigTest extends TestCase
{
    public function testConfigCreation()
    {
        $config = new TelegramConfig([
            'bot_token' => 'test_token',
            'chat_id' => 'test_chat_id'
        ]);

        $this->assertEquals('test_token', $config->getBotToken());
        $this->assertEquals('test_chat_id', $config->getChatId());
        $this->assertEquals('HTML', $config->getParseMode());
        $this->assertEquals(30, $config->getTimeout());
        $this->assertNull($config->getThreadId());
    }

    public function testChatIdValidation()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Chat ID is required');

        new TelegramConfig([
            'bot_token' => 'test_token'
        ]);
    }

    public function testInvalidParseMode()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid parse mode');

        new TelegramConfig([
            'bot_token' => 'test_token',
            'chat_id' => 'test_chat_id',
            'parse_mode' => 'INVALID'
        ]);
    }

    public function testSetters()
    {
        $config = new TelegramConfig([
            'bot_token' => 'test_token',
            'chat_id' => 'test_chat_id'
        ]);

        $config->setChatId('new_chat_id');
        $this->assertEquals('new_chat_id', $config->getChatId());

        $config->setParseMode('Markdown');
        $this->assertEquals('Markdown', $config->getParseMode());

        $config->setTimeout(60);
        $this->assertEquals(60, $config->getTimeout());

        $config->setThreadId(123456789);
        $this->assertEquals(123456789, $config->getThreadId());
    }
}
