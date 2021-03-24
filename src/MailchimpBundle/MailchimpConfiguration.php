<?php

namespace Wgg\MailchimpBundle;

use Pimcore\Cache;
use Pimcore\Config;
use Pimcore\File;
use Symfony\Component\Yaml\Yaml;

class MailchimpConfiguration
{
    private const CACHE_KEY = 'wgg_mailchimp_config_cache';

    private string $filename;

    /**
     * @return array{api_key: string, server_prefix: string, list_id: array<string>}
     */
    public function readConfig(): array
    {
        if (!$data = Cache::load(self::CACHE_KEY)) {
            $data = Yaml::parseFile($this->getConfigFile());
            Cache::save($data, self::CACHE_KEY);
        }

        return $data;
    }

    public function writeConfig(string $apiKey, string $serverPrefix, array $listIds): void
    {
        Cache::remove(self::CACHE_KEY);
        Cache::clearTag(WggMailchimpBundle::CACHE_TAG);
        File::put($this->getConfigFile(),
            Yaml::dump([
                'api_key' => $apiKey,
                'server_prefix' => $serverPrefix,
                'list_id' => $listIds,
            ],
                2,
                4,
                Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE));
    }

    private function getConfigFile(): string
    {
        if (!isset($this->filename)) {
            $this->filename = (string) Config::locateConfigFile('mailchimp.yml');
            if (!file_exists($this->filename)) {
                File::put($this->filename,
                    Yaml::dump([
                        'api_key' => '',
                        'server_prefix' => '',
                        'list_id' => [],
                    ],
                        2,
                        4,
                        Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE)
                );
            }
        }

        return $this->filename;
    }
}
