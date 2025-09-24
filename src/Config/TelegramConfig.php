<?php

namespace Locky42\TelegramLogger\Config;

/**
 * Configuration class for Telegram Logger
 */
class TelegramConfig
{
    private string $botToken;
    private string $chatId;
    private string $parseMode;
    private int $timeout;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->botToken = $config['bot_token'] ?? '';
        $this->chatId = $config['chat_id'] ?? '';
        $this->parseMode = $config['parse_mode'] ?? 'HTML';
        $this->timeout = $config['timeout'] ?? 30;

        $this->validate();
    }

    /**
     * Configuration validation
     */
    private function validate(): void
    {
        if (empty($this->botToken)) {
            throw new \InvalidArgumentException('Bot token is required');
        }

        if (empty($this->chatId)) {
            throw new \InvalidArgumentException('Chat ID is required');
        }

        if (!in_array($this->parseMode, ['HTML', 'Markdown', 'MarkdownV2'])) {
            throw new \InvalidArgumentException('Invalid parse mode. Allowed: HTML, Markdown, MarkdownV2');
        }

        if ($this->timeout <= 0) {
            throw new \InvalidArgumentException('Timeout must be positive integer');
        }
    }

    /**
     * @return string
     */
    public function getBotToken(): string
    {
        return $this->botToken;
    }

    /**
     * @return string
     */
    public function getChatId(): string
    {
        return $this->chatId;
    }

    /**
     * @return string
     */
    public function getParseMode(): string
    {
        return $this->parseMode;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Set a new chat ID
     */
    public function setChatId(string $chatId): void
    {
        $this->chatId = $chatId;
    }

    /**
     * Set a new parse mode
     */
    public function setParseMode(string $parseMode): void
    {
        if (!in_array($parseMode, ['HTML', 'Markdown', 'MarkdownV2'])) {
            throw new \InvalidArgumentException('Invalid parse mode. Allowed: HTML, Markdown, MarkdownV2');
        }
        $this->parseMode = $parseMode;
    }

    /**
     * Set a new timeout
     */
    public function setTimeout(int $timeout): void
    {
        if ($timeout <= 0) {
            throw new \InvalidArgumentException('Timeout must be positive integer');
        }
        $this->timeout = $timeout;
    }
}
