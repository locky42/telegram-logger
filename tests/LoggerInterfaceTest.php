<?php

namespace Locky42\TelegramLogger\Tests;

use PHPUnit\Framework\TestCase;
use Locky42\TelegramLogger\LoggerInterface;

class LoggerInterfaceTest extends TestCase
{
    public function testInterfaceExists()
    {
        $this->assertTrue(interface_exists(LoggerInterface::class));
    }

    public function testInterfaceHasRequiredMethods()
    {
        $reflection = new \ReflectionClass(LoggerInterface::class);
        $methods = $reflection->getMethods();
        $methodNames = array_map(function($method) {
            return $method->getName();
        }, $methods);

        $requiredMethods = ['log', 'info', 'warning', 'error', 'success', 'debug'];
        
        foreach ($requiredMethods as $method) {
            $this->assertContains($method, $methodNames, "Interface must have {$method} method");
        }
    }

    public function testLogMethodSignature()
    {
        $reflection = new \ReflectionClass(LoggerInterface::class);
        $logMethod = $reflection->getMethod('log');
        
        $this->assertEquals('log', $logMethod->getName());
        $this->assertTrue($logMethod->isPublic());
        
        $parameters = $logMethod->getParameters();
        $this->assertCount(2, $parameters);
        
        $this->assertEquals('message', $parameters[0]->getName());
        $this->assertEquals('level', $parameters[1]->getName());
        $this->assertTrue($parameters[1]->isDefaultValueAvailable());
        $this->assertEquals('INFO', $parameters[1]->getDefaultValue());
    }

    public function testOtherMethodsSignatures()
    {
        $reflection = new \ReflectionClass(LoggerInterface::class);
        $methods = ['info', 'warning', 'error', 'success', 'debug'];
        
        foreach ($methods as $methodName) {
            $method = $reflection->getMethod($methodName);
            $parameters = $method->getParameters();
            
            $this->assertCount(1, $parameters);
            $this->assertEquals('message', $parameters[0]->getName());
            $this->assertTrue($method->isPublic());
        }
    }
}
