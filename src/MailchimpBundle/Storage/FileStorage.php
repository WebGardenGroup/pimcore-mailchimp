<?php

namespace Wgg\MailchimpBundle\Storage;

use Pimcore\Config;
use Pimcore\File;
use Symfony\Component\Yaml\Yaml;

use function file_exists;

class FileStorage implements StorageInterface
{
    public function read(): array
    {
        return Yaml::parseFile($this->getConfigFile());
    }

    public function write(string $apiKey, string $serverPrefix, array $listIds): void
    {
        $this->doWrite($this->getConfigFile(), $apiKey, $serverPrefix, $listIds);
    }

    private function getConfigFile(): string
    {
        $filename = (string) Config::locateConfigFile('mailchimp.yml');
        if (!file_exists($filename)) {
            $this->doWrite($filename, '', '', []);
        }

        return $filename;
    }

    private function doWrite(string $filename, string $apiKey, string $serverPrefix, array $listIds): void
    {
        File::put($filename,
            Yaml::dump([
                'api_key' => $apiKey,
                'server_prefix' => $serverPrefix,
                'list_id' => $listIds,
            ],
                2,
                4,
                Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE));
    }
}
