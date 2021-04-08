<?php

namespace Wgg\MailchimpBundle;

use Pimcore\Cache;
use Wgg\MailchimpBundle\Storage\StorageInterface;

class MailchimpConfiguration
{
    private const CACHE_KEY = 'wgg_mailchimp_config_cache';

    private StorageInterface $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return array{api_key: string, server_prefix: string, list_id: array<string>}
     */
    public function readConfig(): array
    {
        if (!$data = Cache::load(self::CACHE_KEY)) {
            $data = $this->storage->read();
            Cache::save($data, self::CACHE_KEY);
        }

        return $data;
    }

    public function writeConfig(string $apiKey, string $serverPrefix, array $listIds): void
    {
        Cache::remove(self::CACHE_KEY);
        Cache::clearTag(WggMailchimpBundle::CACHE_TAG);
        $this->storage->write($apiKey, $serverPrefix, $listIds);
    }
}
