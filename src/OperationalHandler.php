<?php

namespace Noxomix\OperationalMonolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OperationalHandler extends AbstractProcessingHandler
{
    protected Client $client;

    protected array $avatars = [
        100 => '🔧',
        200 => 'ℹ️',
        250 => '📌',
        300 => '⚠️',
        400 => '❌',
        500 => '🔥',
        550 => '🚨',
        600 => '🆘',
    ];

    public function __construct(
        protected string $apiKey,
        Level|int $level = Level::Debug,
        bool $bubble = true,
        protected string $endpoint = 'https://api.operational.co/api/v1/log'
    ) {
        parent::__construct($level, $bubble);
        $this->client = new Client();
    }

    protected function write(LogRecord $record): void
    {
        try {
            $this->client->post($this->endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'name'    => $record->message,
                    'avatar'  => $this->getAvatar($record->level),
                    'content' => $record->context,
                ],
            ]);
        } catch (GuzzleException $e) {
            error_log('[OperationalHandler] ' . $e->getMessage());
        }
    }

    public function getAvatar(Level $level): ?string
    {
        return $this->avatars[$level->value] ?? null;
    }

    public function setAvatar(Level $level, string $avatar): void
    {
        $this->avatars[$level->value] = $avatar;
    }

    public function setAvatars(array $avatars): void
    {
        $this->avatars = $avatars;
    }

    public function getAvatars(): array
    {
        return $this->avatars;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }
}

