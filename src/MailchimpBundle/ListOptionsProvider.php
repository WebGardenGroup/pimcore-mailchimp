<?php

namespace Wg\MailchimpBundle;

use Pimcore\Cache;
use Pimcore\Model\DataObject\ClassDefinition\DynamicOptionsProvider\MultiSelectOptionsProviderInterface;
use Pimcore\Model\DataObject\ClassDefinition\DynamicOptionsProvider\SelectOptionsProviderInterface;

use function implode;
use function md5;

class ListOptionsProvider implements MultiSelectOptionsProviderInterface, SelectOptionsProviderInterface
{
    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions($context, $fieldDefinition): array
    {
        return $this->getRawOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function hasStaticOptions($context, $fieldDefinition): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultValue($context, $fieldDefinition)
    {
        return null;
    }

    /**
     * @return array<array{key: string, value: string}>
     */
    public function getRawOptions(): array
    {
        $data = [];
        $listIds = $this->apiClient->getListIds();
        if ($listIds) {
            $cacheKey = $this->getCacheKey($listIds);
            if (!$data = Cache::load($cacheKey)) {
                foreach ($listIds as $listId) {
                    $list = $this->apiClient->lists->getList($listId, 'name,id');
                    $data[] = [
                        'key' => $list->name,
                        'value' => $list->id,
                    ];
                }
                Cache::save($data, $cacheKey, [WgMailchimpBundle::CACHE_TAG]);
            }
        }

        return $data;
    }

    private function getCacheKey(array $listIds): string
    {
        return md5(implode('|', $listIds));
    }
}
