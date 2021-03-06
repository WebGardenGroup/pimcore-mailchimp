<?php

namespace Wgg\MailchimpBundle\Command;

use Pimcore\Console\Style\PimcoreStyle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wgg\MailchimpBundle\Util\ApiClient;

class GetListCommand extends Command
{
    protected static $defaultName = 'wg:mailchimp:get-list';

    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct();
        $this->apiClient = $apiClient;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Gets info about lists from mailchimp server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new PimcoreStyle($input, $output);

        $io->title('Mailchimp get list');

        if ($listIds = $this->apiClient->getListIds()) {
            $listsData = [];
            foreach ($listIds as $listId) {
                $list = $this->apiClient->lists->getList($listId, 'name,id');
                $listsData[] = [
                    $list->id,
                    $list->name,
                ];
            }

            $io->table(['ID', 'NAME'], $listsData);

            return 0;
        } else {
            $io->error('No audience/list is configured');

            return 1;
        }
    }
}
