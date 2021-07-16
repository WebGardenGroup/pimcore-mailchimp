<?php

namespace Wgg\MailchimpBundle\Storage;

use Pimcore\Model\Tool\SettingsStore;

use function explode;
use function implode;

class SettingsStoreStorage implements StorageInterface
{
    private const SCOPE = 'wgg_mailchimp';

    public function read(): array
    {
        $data = [
            'api_key' => '',
            'server_prefix' => '',
            'list_id' => [],
        ];

        $ids = SettingsStore::getIdsByScope(self::SCOPE);
        foreach ($ids as $id) {
            /** @var SettingsStore $settingsStore */
            $settingsStore = SettingsStore::get($id, self::SCOPE);
            /** @var string $rawData */
            $rawData = $settingsStore->getData();
            $data[$id] = 'list_id' === $id ? explode(',', $rawData) : $rawData;
        }

        return $data;
    }

    public function write(string $apiKey, string $serverPrefix, array $listIds): void
    {
        SettingsStore::set('api_key', $apiKey, 'string', self::SCOPE);
        SettingsStore::set('server_prefix', $serverPrefix, 'string', self::SCOPE);
        SettingsStore::set('list_id', implode(',', $listIds), 'string', self::SCOPE);
    }
}
