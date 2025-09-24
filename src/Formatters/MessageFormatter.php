<?php

namespace Locky42\TelegramLogger\Formatters;

/**
 * Message formatter for Telegram
 */
class MessageFormatter
{
    private array $levelEmojis = [
        'INFO' => 'ℹ️',
        'WARNING' => '⚠️',
        'ERROR' => '❌',
        'SUCCESS' => '✅',
        'DEBUG' => '🐛'
    ];

    private array $levelColors = [
        'INFO' => '#2196F3',
        'WARNING' => '#FF9800',
        'ERROR' => '#F44336',
        'SUCCESS' => '#4CAF50',
        'DEBUG' => '#9C27B0'
    ];

    /**
     * Formats a message with log level and time
     */
    public function format(string $message, string $level, array $options = []): string
    {
        $includeTimestamp = $options['include_timestamp'] ?? true;
        $includeEmoji = $options['include_emoji'] ?? true;
        $includeLevel = $options['include_level'] ?? true;

        $formattedMessage = '';

        if ($includeEmoji) {
            $formattedMessage .= $this->getLevelEmoji($level) . ' ';
        }

        if ($includeLevel) {
            $formattedMessage .= "<b>[{$level}]</b> ";
        }

        if ($includeTimestamp) {
            $timestamp = date('Y-m-d H:i:s');
            $formattedMessage .= $timestamp . "\n";
        }

        $formattedMessage .= $message;

        return $formattedMessage;
    }

    /**
     * Formats a message as a card
     */
    public function formatAsCard(string $title, string $message, string $level, array $fields = []): string
    {
        $emoji = $this->getLevelEmoji($level);
        $timestamp = date('Y-m-d H:i:s');

        $card = "{$emoji} <b>{$title}</b>\n";
        $card .= "⏰ {$timestamp}\n";
        $card .= "📝 <b>Message:</b>\n{$message}\n";

        if (!empty($fields)) {
            $card .= "\n<b>Additional information:</b>\n";
            foreach ($fields as $key => $value) {
                $card .= "• <b>{$key}:</b> {$value}\n";
            }
        }

        return $card;
    }

    /**
     * Escapes special HTML characters
     */
    public function escapeHtml(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Escapes special Markdown characters
     */
    public function escapeMarkdown(string $text): string
    {
        $specialChars = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
        
        foreach ($specialChars as $char) {
            $text = str_replace($char, '\\' . $char, $text);
        }
        
        return $text;
    }

    /**
     * Returns emoji for log level
     */
    public function getLevelEmoji(string $level): string
    {
        return $this->levelEmojis[strtoupper($level)] ?? 'ℹ️';
    }

    /**
     * Returns color for log level
     */
    public function getLevelColor(string $level): string
    {
        return $this->levelColors[strtoupper($level)] ?? '#2196F3';
    }

    /**
     * Adds new emoji for level
     */
    public function setLevelEmoji(string $level, string $emoji): void
    {
        $this->levelEmojis[strtoupper($level)] = $emoji;
    }

    /**
     * Adds new color for level
     */
    public function setLevelColor(string $level, string $color): void
    {
        $this->levelColors[strtoupper($level)] = $color;
    }
}
