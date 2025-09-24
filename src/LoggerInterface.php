<?php

namespace Locky42\TelegramLogger;

/**
 * Logger interface
 */
interface LoggerInterface
{
    /**
     * Main logging method
     */
    public function log(string $message, string $level = 'INFO'): bool;

    /**
     * Information message
     */
    public function info(string $message): bool;

    /**
     * Warning message
     */
    public function warning(string $message): bool;

    /**
     * Error message
     */
    public function error(string $message): bool;

    /**
     * Success message
     */
    public function success(string $message): bool;

    /**
     * Debug message
     */
    public function debug(string $message): bool;
}
