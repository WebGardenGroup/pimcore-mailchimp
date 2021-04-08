<?php

namespace Wgg\MailchimpBundle\Tests;

use Pimcore\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Wgg\MailchimpBundle\MailchimpConfiguration;

class MailchimpConfigurationFileStorageTest extends KernelTestCase
{
    private Filesystem $fs;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->fs = new Filesystem();
    }

    protected function tearDown(): void
    {
        $this->fs->remove(PIMCORE_CONFIGURATION_DIRECTORY.'/mailchimp.yml');
        parent::tearDown();
    }

    public function testReadConfigDefault(): void
    {
        /** @var MailchimpConfiguration $mailchimpConfiguration */
        $mailchimpConfiguration = self::$container->get('file.'.MailchimpConfiguration::class);

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
        $mailchimpConfiguration = self::$container->get('file.'.MailchimpConfiguration::class);

        $apiKey = 'testing';
        $serverPrefix = 'testing';
        $listIds = ['testing1', 'testing2'];

        $mailchimpConfiguration->writeConfig($apiKey, $serverPrefix, $listIds);

        $this->assertFileEquals(__DIR__.'/fixtures/mailchimp.yml', PIMCORE_CONFIGURATION_DIRECTORY.'/mailchimp.yml');
    }
}
