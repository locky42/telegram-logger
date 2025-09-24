<?php

namespace Locky42\TelegramLogger\Tests;

use PHPUnit\Framework\TestCase;
use Locky42\TelegramLogger\Formatters\MessageFormatter;

class MessageFormatterTest extends TestCase
{
    private MessageFormatter $formatter;

    protected function setUp(): void
    {
        $this->formatter = new MessageFormatter();
    }

    public function testBasicFormatting()
    {
        $message = 'Test message';
        $level = 'INFO';
        
        $result = $this->formatter->format($message, $level);
        
        $this->assertStringContainsString('ℹ️', $result);
        $this->assertStringContainsString('[INFO]', $result);
        $this->assertStringContainsString($message, $result);
        $this->assertStringContainsString(date('Y-m-d'), $result);
    }

    public function testFormattingWithOptions()
    {
        $message = 'Test message';
        $level = 'ERROR';
        $options = [
            'include_timestamp' => false,
            'include_emoji' => false,
            'include_level' => true
        ];
        
        $result = $this->formatter->format($message, $level, $options);
        
        $this->assertStringNotContainsString('❌', $result);
        $this->assertStringContainsString('[ERROR]', $result);
        $this->assertStringContainsString($message, $result);
        $this->assertStringNotContainsString(date('Y-m-d'), $result);
    }

    public function testCardFormatting()
    {
        $title = 'Test Title';
        $message = 'Test message';
        $level = 'WARNING';
        $fields = [
            'Field1' => 'Value1',
            'Field2' => 'Value2'
        ];
        
        $result = $this->formatter->formatAsCard($title, $message, $level, $fields);
        
        $this->assertStringContainsString($title, $result);
        $this->assertStringContainsString($message, $result);
        $this->assertStringContainsString('⚠️', $result);
        $this->assertStringContainsString('Field1', $result);
        $this->assertStringContainsString('Value1', $result);
        $this->assertStringContainsString('Field2', $result);
        $this->assertStringContainsString('Value2', $result);
    }

    public function testGetLevelEmoji()
    {
        $this->assertEquals('ℹ️', $this->formatter->getLevelEmoji('INFO'));
        $this->assertEquals('⚠️', $this->formatter->getLevelEmoji('WARNING'));
        $this->assertEquals('❌', $this->formatter->getLevelEmoji('ERROR'));
        $this->assertEquals('✅', $this->formatter->getLevelEmoji('SUCCESS'));
        $this->assertEquals('🐛', $this->formatter->getLevelEmoji('DEBUG'));
        $this->assertEquals('ℹ️', $this->formatter->getLevelEmoji('UNKNOWN'));
    }

    public function testGetLevelColor()
    {
        $this->assertEquals('#2196F3', $this->formatter->getLevelColor('INFO'));
        $this->assertEquals('#FF9800', $this->formatter->getLevelColor('WARNING'));
        $this->assertEquals('#F44336', $this->formatter->getLevelColor('ERROR'));
        $this->assertEquals('#4CAF50', $this->formatter->getLevelColor('SUCCESS'));
        $this->assertEquals('#9C27B0', $this->formatter->getLevelColor('DEBUG'));
        $this->assertEquals('#2196F3', $this->formatter->getLevelColor('UNKNOWN'));
    }

    public function testSetLevelEmoji()
    {
        $this->formatter->setLevelEmoji('CUSTOM', '🔧');
        $this->assertEquals('🔧', $this->formatter->getLevelEmoji('CUSTOM'));
    }

    public function testSetLevelColor()
    {
        $this->formatter->setLevelColor('CUSTOM', '#123456');
        $this->assertEquals('#123456', $this->formatter->getLevelColor('CUSTOM'));
    }

    public function testEscapeHtml()
    {
        $input = '<script>alert("test")</script>';
        $expected = '&lt;script&gt;alert(&quot;test&quot;)&lt;/script&gt;';
        
        $result = $this->formatter->escapeHtml($input);
        
        $this->assertEquals($expected, $result);
    }

    public function testEscapeMarkdown()
    {
        $input = '*bold* _italic_ [link](url)';
        $result = $this->formatter->escapeMarkdown($input);
        
        $this->assertStringContainsString('\\*bold\\*', $result);
        $this->assertStringContainsString('\\_italic\\_', $result);
        $this->assertStringContainsString('\\[link\\]', $result);
        $this->assertStringContainsString('\\(url\\)', $result);
    }

    public function testCaseInsensitiveLevel()
    {
        $this->assertEquals('ℹ️', $this->formatter->getLevelEmoji('info'));
        $this->assertEquals('ℹ️', $this->formatter->getLevelEmoji('Info'));
        $this->assertEquals('ℹ️', $this->formatter->getLevelEmoji('INFO'));
    }
}
