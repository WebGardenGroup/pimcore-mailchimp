<?php

namespace Wgg\MailchimpBundle;

use Pimcore\Config;
use Pimcore\File;
use Symfony\Component\Yaml\Yaml;

class MailchimpConfiguration
{
    private string $filename;

    public function readConfig(): array
    {
        return Yaml::parseFile($this->getConfigFile());
    }

    public function writeConfig(array $data): void
    {
        File::put($this->getConfigFile(), Yaml::dump($data));
    }

    private function getConfigFile(): string
    {
        if (!isset($this->filename)) {
            $this->filename = (string) Config::locateConfigFile('mailchimp.yml');
            if (!file_exists($this->filename)) {
                File::put($this->filename,
                    Yaml::dump([
                        'config' => [
                            'api_key' => '',
                            'server_prefix' => '',
                            'list_id' => [],
                        ],
                        'version' => 0,
                    ],
                        5
                    )
                );
            }
        }

        return $this->filename;
    }
}
