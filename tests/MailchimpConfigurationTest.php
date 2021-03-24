<?php

namespace Wgg\MailchimpBundle\Tests;

use Pimcore\Test\KernelTestCase;
use Wgg\MailchimpBundle\MailchimpConfiguration;

class MailchimpConfigurationTest extends KernelTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
    }

    public function testReadConfigDefault(): void
    {
        /** @var MailchimpConfiguration $mailchimpConfiguration */
        $mailchimpConfiguration = self::$container->get(MailchimpConfiguration::class);

        $defaultConfig = $mailchimpConfiguration->readConfig();

        $this->assertIsArray($defaultConfig);
        $this->assertSame([
            'api_key' => '',
            'server_prefix' => '',
            'list_id' => [],
        ],
            $defaultConfig);

        $this->assertFileExists(PIMCORE_CONFIGURATION_DIRECTORY.'/mailchimp.yml');
    }

    public function testWriteConfig(): void
    {
        /** @var MailchimpConfiguration $mailchimpConfiguration */
        $mailchimpConfiguration = self::$container->get(MailchimpConfiguration::class);

        $apiKey = 'testing';
        $serverPrefix = 'testing';
        $listIds = ['testing1', 'testing2'];

        $mailchimpConfiguration->writeConfig($apiKey, $serverPrefix, $listIds);

        $this->assertFileEquals(__DIR__.'/fixtures/mailchimp.yml', PIMCORE_CONFIGURATION_DIRECTORY.'/mailchimp.yml');
    }

    protected function tearDown(): void
    {
        if (file_exists(PIMCORE_CONFIGURATION_DIRECTORY.'/mailchimp.yml')) {
            @unlink(PIMCORE_CONFIGURATION_DIRECTORY.'/mailchimp.yml');
        }
    }
}
