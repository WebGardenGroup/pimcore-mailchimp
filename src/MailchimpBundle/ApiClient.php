<?php

namespace Wg\MailchimpBundle;

use MailchimpMarketing\Api\ListsApi;
use MailchimpMarketing\Api\PingApi;
use MailchimpMarketing\ApiClient as BaseApiClient;

use function mb_strtolower;
use function md5;

/**
 * @property ListsApi $lists
 * @property PingApi  $ping
 */
class ApiClient extends BaseApiClient
{
    private ?array $listIds;

    public function __construct(MailchimpConfiguration $configuration)
    {
        parent::__construct();
        $config = $configuration->readConfig();
        $this->setConfig([
            'apiKey' => $config['config']['api_key'],
            'server' => $config['config']['server_prefix'],
        ]);
        $this->listIds = $config['config']['list_id'];
    }

    public function getListIds(): ?array
    {
        return $this->listIds;
    }

    public function addListMember(string $listId, string $email, string $name, string $language): void
    {
        $subscriberHash = md5(mb_strtolower($email));

        $this->lists->setListMember($listId,
            $subscriberHash,
            [
                'email_address' => $email,
                'status_if_new' => 'pending',
                'status' => 'subscribed',
                'language' => $language,
                'merge_fields' => [
                    'NAME' => $name,
                ],
            ]);
    }
}
