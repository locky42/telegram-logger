<?php

namespace Locky42\TelegramLogger\Tests;

use PHPUnit\Framework\TestCase;
use Locky42\TelegramLogger\Exceptions\TelegramLoggerException;

class TelegramLoggerExceptionTest extends TestCase
{
    public function testExceptionCreation()
    {
        $message = 'Test exception message';
        $code = 123;
        
        $exception = new TelegramLoggerException($message, $code);
        
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    public function testExceptionWithPreviousException()
    {
        $previousException = new \Exception('Previous exception');
        $exception = new TelegramLoggerException('New exception', 456, $previousException);
        
        $this->assertEquals($previousException, $exception->getPrevious());
    }

    public function testDefaultValues()
    {
        $exception = new TelegramLoggerException();
        
        $this->assertEquals('', $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    public function testExceptionCanBeThrown()
    {
        $this->expectException(TelegramLoggerException::class);
        $this->expectExceptionMessage('Test throw');
        $this->expectExceptionCode(789);
        
        throw new TelegramLoggerException('Test throw', 789);
    }
}
