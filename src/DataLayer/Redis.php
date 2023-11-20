<?php

declare(strict_types=1);

namespace App\DataLayer;

use Predis\Client;

class Redis
{
    private static self $redis;
    private Client $client;

    private function __construct()
    {
        $this->client = new Client('redis://redis');
    }

    private function __clone(): void {}

    public static function getInstance(): self
    {
        if (isset(self::$redis)) {
            return self::$redis;
        }

        self::$redis = new self();

        return self::$redis;
    }

    public function hset(string $key, string $field, string $value): void
    {
        $this->client->hset($key, $field, $value);
    }

    public function hdel(string $key, string $field)
    {
        $this->client->hdel($key, [$field]);
    }

    public function hget(string $key, string $field): ?string
    {
        return $this->client->hget($key, $field);
    }

    public function hgetall(string $sheetId): array
    {
        return $this->client->hgetall($sheetId);
    }

    public function set(string $key, string $value)
    {
        $this->client->set($key, $value);
    }

    public function get(string $key)
    {
        $this->client->get($key);
    }
}
