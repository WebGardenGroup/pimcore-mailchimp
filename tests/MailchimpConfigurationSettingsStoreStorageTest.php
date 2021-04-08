<?php

namespace Wgg\MailchimpBundle\Tests;

use function implode;
use Pimcore\Model\Tool\SettingsStore;
use Pimcore\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Wgg\MailchimpBundle\MailchimpConfiguration;

class MailchimpConfigurationSettingsStoreStorageTest extends KernelTestCase
{
    private Filesystem $fs;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->fs = new Filesystem();
        $this->fs->copy(__DIR__.'/fixtures/database.sqlite3', PIMCORE_PRIVATE_VAR.'/database.sqlite3', true);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->fs->remove(PIMCORE_PRIVATE_VAR.'/database.sqlite3');
    }

    public function testReadConfigDefault(): void
    {
        /** @var MailchimpConfiguration $mailchimpConfiguration */
        $mailchimpConfiguration = self::$container->get('settingsStore.'.MailchimpConfiguration::class);

        $defaultConfig = $mailchimpConfiguration->readConfig();

        $this->assertIsArray($defaultConfig);
        $this->assertSame([
            'api_key' => '',
            'server_prefix' => '',
            'list_id' => [],
        ],
            $defaultConfig);

        $this->assertNull(SettingsStore::get('api_key', 'wgg_mailchimp'));
        $this->assertNull(SettingsStore::get('server_prefix', 'wgg_mailchimp'));
        $this->assertNull(SettingsStore::get('list_id', 'wgg_mailchimp'));
    }

    public function testWriteConfig(): void
    {
        /** @var MailchimpConfiguration $mailchimpConfiguration */
        $mailchimpConfiguration = self::$container->get('settingsStore.'.MailchimpConfiguration::class);

        $apiKey = 'testing';
        $serverPrefix = 'testing';
        $listIds = ['testing1', 'testing2'];

        $mailchimpConfiguration->writeConfig($apiKey, $serverPrefix, $listIds);

        $dbApiKeyObject = SettingsStore::get('api_key', 'wgg_mailchimp');
        $this->assertNotNull($dbApiKeyObject);
        $this->assertSame('testing', $dbApiKeyObject->getData());

        $dbServerPrefixObject = SettingsStore::get('server_prefix', 'wgg_mailchimp');
        $this->assertNotNull($dbServerPrefixObject);
        $this->assertSame('testing', $dbServerPrefixObject->getData());

        $dbListIdObject = SettingsStore::get('list_id', 'wgg_mailchimp');
        $this->assertNotNull($dbListIdObject);
        $this->assertSame(implode(',', $listIds), $dbListIdObject->getData());
    }
}
