<?php

namespace Locky42\TelegramLogger;

use Locky42\TelegramLogger\Config\TelegramConfig;
use Locky42\TelegramLogger\Exceptions\TelegramLoggerException;
use Locky42\TelegramLogger\Formatters\MessageFormatter;

/**
 * Main class for logging messages to Telegram
 */
class TelegramLogger implements LoggerInterface
{
    private TelegramConfig $config;
    private MessageFormatter $formatter;
    private string $apiUrl;

    /**
     * @param string|TelegramConfig $chatIdOrConfig
     * @param string|null $botToken
     */
    public function __construct($chatIdOrConfig, ?string $botToken = null)
    {
        if ($chatIdOrConfig instanceof TelegramConfig) {
            $this->config = $chatIdOrConfig;
        } else {
            $this->config = new TelegramConfig([
                'chat_id' => $chatIdOrConfig,
                'bot_token' => $botToken
            ]);
        }

        $this->formatter = new MessageFormatter();
        $this->apiUrl = "https://api.telegram.org/bot{$this->config->getBotToken()}/sendMessage";
    }

    /**
     * Sends a message to Telegram
     *
     * @param string $message
     * @param string $level
     * @param array $options
     * @return bool
     * @throws TelegramLoggerException
     */
    public function log(string $message, string $level = 'INFO', array $options = []): bool
    {
        $formattedMessage = $this->formatter->format($message, $level, $options);
        
        return $this->sendMessage($formattedMessage);
    }

    /**
     * Sends a message as a card
     */
    public function logCard(string $title, string $message, string $level = 'INFO', array $fields = []): bool
    {
        $formattedMessage = $this->formatter->formatAsCard($title, $message, $level, $fields);
        
        return $this->sendMessage($formattedMessage);
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
     * Sends a message via Telegram API
     */
    protected function sendMessage(string $message): bool
    {
        $data = [
            'chat_id' => $this->config->getChatId(),
            'text' => $message,
            'parse_mode' => $this->config->getParseMode()
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->config->getTimeout(),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new TelegramLoggerException("cURL error: {$error}");
        }

        if ($httpCode !== 200) {
            $responseData = json_decode($response, true);
            $errorDescription = $responseData['description'] ?? 'Unknown error';
            throw new TelegramLoggerException("Telegram API error: {$errorDescription} (HTTP {$httpCode})");
        }

        $responseData = json_decode($response, true);
        
        if (!$responseData['ok']) {
            throw new TelegramLoggerException("Telegram API returned error: " . ($responseData['description'] ?? 'Unknown error'));
        }

        return true;
    }
}
