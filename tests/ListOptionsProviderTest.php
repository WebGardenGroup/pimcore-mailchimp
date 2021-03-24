<?php

namespace Wgg\MailchimpBundle\Tests;

use MailchimpMarketing\Api\ListsApi;
use PHPUnit\Framework\MockObject\MockObject;
use Pimcore\Test\KernelTestCase;
use stdClass;
use Wgg\MailchimpBundle\ApiClient;
use Wgg\MailchimpBundle\ListOptionsProvider;

use function copy;
use function ucfirst;

class ListOptionsProviderTest extends KernelTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
        copy(__DIR__.'/fixtures/mailchimp.yml', PIMCORE_CONFIGURATION_DIRECTORY.'/mailchimp.yml');
    }

    public static function tearDownAfterClass(): void
    {
        if (file_exists(PIMCORE_CONFIGURATION_DIRECTORY.'/mailchimp.yml')) {
            @unlink(PIMCORE_CONFIGURATION_DIRECTORY.'/mailchimp.yml');
        }
    }

    public function testGetRawOptions(): void
    {
        /** @var ListOptionsProvider $listOptionsProvider */
        $listOptionsProvider = self::$container->get(ListOptionsProvider::class);

        /** @var ApiClient $apiClient */
        $apiClient = self::$container->get(ApiClient::class);

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

    protected function setUp(): void
    {
        $this->createApiClientMock();
    }

    private function createApiClientMock(): void
    {
        /** @var ApiClient $apiClient */
        $apiClient = self::$container->get(ApiClient::class);
        $apiClient->lists = $this->createMock(ListsApi::class);
    }
}
