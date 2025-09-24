<?php

namespace Locky42\TelegramLogger\Factory;

use Locky42\TelegramLogger\Config\TelegramConfig;
use Locky42\TelegramLogger\TelegramLogger;
use Locky42\TelegramLogger\AsyncTelegramLogger;
use Locky42\TelegramLogger\LoggerInterface;

/**
 * Factory for creating loggers
 */
class LoggerFactory
{
    /**
     * Creates a synchronous logger
     */
    public static function createSyncLogger(string $botToken, string $chatId, array $options = []): LoggerInterface
    {
        $config = new TelegramConfig(array_merge([
            'bot_token' => $botToken,
            'chat_id' => $chatId
        ], $options));

        return new TelegramLogger($config);
    }

    /**
     * Creates an asynchronous logger
     */
    public static function createAsyncLogger(string $botToken, string $chatId, array $options = []): LoggerInterface
    {
        $config = new TelegramConfig(array_merge([
            'bot_token' => $botToken,
            'chat_id' => $chatId
        ], $options));

        return new AsyncTelegramLogger($config);
    }

    /**
     * Creates a logger from configuration
     */
    public static function createFromConfig(TelegramConfig $config, bool $async = false): LoggerInterface
    {
        return $async ? new AsyncTelegramLogger($config) : new TelegramLogger($config);
    }

    /**
     * Creates a logger from configuration array
     */
    public static function createFromArray(array $config, bool $async = false): LoggerInterface
    {
        $telegramConfig = new TelegramConfig($config);
        
        return $async ? new AsyncTelegramLogger($telegramConfig) : new TelegramLogger($telegramConfig);
    }

    /**
     * Creates a logger from configuration file
     */
    public static function createFromFile(string $configPath, bool $async = false): LoggerInterface
    {
        if (!file_exists($configPath)) {
            throw new \InvalidArgumentException("Configuration file not found: {$configPath}");
        }

        $extension = pathinfo($configPath, PATHINFO_EXTENSION);
        
        switch ($extension) {
            case 'json':
                $config = json_decode(file_get_contents($configPath), true);
                break;
            case 'php':
                $config = require $configPath;
                break;
            default:
                throw new \InvalidArgumentException("Unsupported configuration file format: {$extension}");
        }

        if (!is_array($config)) {
            throw new \InvalidArgumentException("Invalid configuration format");
        }

        return self::createFromArray($config, $async);
    }
}
