<?php

namespace Wg\MailchimpBundle;

use Pimcore\Config;
use Pimcore\File;
use Symfony\Component\Yaml\Yaml;

class MailchimpConfiguration
{
    private string $filename;

    public function readConfig()
    {
        return Yaml::parseFile($this->getConfigFile());
    }

    public function writeConfig($data)
    {
        File::put($this->getConfigFile(), Yaml::dump($data));
    }

    private function getConfigFile()
    {
        if (!isset($this->filename)) {
            $this->filename = (string) Config::locateConfigFile('mailchimp.yml');
            if (!file_exists($this->filename)) {
                File::put($this->filename, Yaml::dump(['config' => []], 5));
            }
        }

        return $this->filename;
    }
}
