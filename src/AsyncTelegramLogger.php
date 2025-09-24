<?php

namespace Locky42\TelegramLogger;

use Locky42\TelegramLogger\Config\TelegramConfig;
use Locky42\TelegramLogger\Exceptions\TelegramLoggerException;
use Locky42\TelegramLogger\Formatters\MessageFormatter;

/**
 * Asynchronous logger for Telegram (without waiting for response)
 */
class AsyncTelegramLogger implements LoggerInterface
{
    private TelegramConfig $config;
    private MessageFormatter $formatter;
    private string $apiUrl;

    /**
     * @param string|TelegramConfig $botTokenOrConfig
     * @param string|null $chatId
     */
    public function __construct($botTokenOrConfig, ?string $chatId = null)
    {
        if ($botTokenOrConfig instanceof TelegramConfig) {
            $this->config = $botTokenOrConfig;
        } else {
            $this->config = new TelegramConfig([
                'bot_token' => $botTokenOrConfig,
                'chat_id' => $chatId
            ]);
        }

        $this->formatter = new MessageFormatter();
        $this->apiUrl = "https://api.telegram.org/bot{$this->config->getBotToken()}/sendMessage";
    }

    /**
     * Sends a message to Telegram asynchronously
     */
    public function log(string $message, string $level = 'INFO'): bool
    {
        $formattedMessage = $this->formatter->format($message, $level);
        
        return $this->sendMessageAsync($formattedMessage);
    }

    /**
     * Sends an informational message
     */
    public function info(string $message): bool
    {
        return $this->log($message, 'INFO');
    }

    /**
     * Sends a warning
     */
    public function warning(string $message): bool
    {
        return $this->log($message, 'WARNING');
    }

    /**
     * Sends an error message
     */
    public function error(string $message): bool
    {
        return $this->log($message, 'ERROR');
    }

    /**
     * Sends a success message
     */
    public function success(string $message): bool
    {
        return $this->log($message, 'SUCCESS');
    }

    /**
     * Sends a debug message
     */
    public function debug(string $message): bool
    {
        return $this->log($message, 'DEBUG');
    }

    /**
     * Sends a message asynchronously (without waiting for response)
     */
    protected function sendMessageAsync(string $message): bool
    {
        $data = [
            'chat_id' => $this->config->getChatId(),
            'text' => $message,
            'parse_mode' => $this->config->getParseMode()
        ];

        $postData = http_build_query($data);

        // Use fsockopen for asynchronous sending
        $parts = parse_url($this->apiUrl);
        $host = $parts['host'];
        $path = $parts['path'];

        $out = "POST {$path} HTTP/1.1\r\n";
        $out .= "Host: {$host}\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "Content-Length: " . strlen($postData) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";
        $out .= $postData;

        $fp = fsockopen('ssl://' . $host, 443, $errno, $errstr, 5);
        
        if (!$fp) {
            throw new TelegramLoggerException("Socket error: {$errstr} ({$errno})");
        }

        fwrite($fp, $out);
        fclose($fp);

        return true;
    }
}
