<?php

namespace Wg\MailchimpBundle\Command;

use Pimcore\Console\Style\PimcoreStyle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wg\MailchimpBundle\ApiClient;

class PingCommand extends Command
{
    protected static $defaultName = 'wg:mailchimp:ping';

    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct();
        $this->apiClient = $apiClient;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Pings mailchimp server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new PimcoreStyle($input, $output);

        $io->title('Mailchimp ping');

        $io->success($this->apiClient->ping->get()->health_status);

        return 0;
    }
}
