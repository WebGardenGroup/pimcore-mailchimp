<?php

namespace Wgg\MailchimpBundle\Tests;

use MailchimpMarketing\Api\ListsApi;
use PHPUnit\Framework\MockObject\MockObject;
use Pimcore\Test\KernelTestCase;
use stdClass;
use Symfony\Component\Filesystem\Filesystem;
use Wgg\MailchimpBundle\ApiClient;
use Wgg\MailchimpBundle\ListOptionsProvider;

use function ucfirst;

class ListOptionsProviderTest extends KernelTestCase
{
    private Filesystem $fs;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->fs = new Filesystem();
        $this->fs->copy(__DIR__.'/fixtures/mailchimp.yml', PIMCORE_CONFIGURATION_DIRECTORY.'/mailchimp.yml', true);

        $this->createApiClientMock();
    }

    protected function tearDown(): void
    {
        $this->fs->remove(PIMCORE_CONFIGURATION_DIRECTORY.'/mailchimp.yml');
        parent::tearDown();
    }

    public function testGetRawOptions(): void
    {
        /** @var ListOptionsProvider $listOptionsProvider */
        $listOptionsProvider = self::getContainer()->get(ListOptionsProvider::class);

        /** @var ApiClient $apiClient */
        $apiClient = self::getContainer()->get(ApiClient::class);

        /** @var MockObject $listsApi */
        $listsApi = $apiClient->lists;

        $listsApi
            ->expects($this->exactly(2))
            ->method('getList')
            ->willReturnCallback(function ($id) {
                $result = new stdClass();
                $result->name = ucfirst($id);
                $result->id = $id;

                return $result;
            });

        $options = $listOptionsProvider->getRawOptions();

        $this->assertSame([
            [
                'key' => 'Testing1',
                'value' => 'testing1',
            ],
            [
                'key' => 'Testing2',
                'value' => 'testing2',
            ],
        ],
            $options);
    }

    private function createApiClientMock(): void
    {
        /** @var ApiClient $apiClient */
        $apiClient = self::getContainer()->get(ApiClient::class);
        $apiClient->lists = $this->createMock(ListsApi::class);
    }
}
