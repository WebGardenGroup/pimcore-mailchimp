<?php

namespace Wg\MailchimpBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Wg\MailchimpBundle\ListOptionsProvider;

class MailchimpExtension extends AbstractExtension
{
    private ListOptionsProvider $optionsProvider;

    public function __construct(ListOptionsProvider $optionsProvider)
    {
        $this->optionsProvider = $optionsProvider;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('mailchimp_list_store', [$this, 'getListStore']),
        ];
    }

    public function getListStore(): array
    {
        $options = $this->optionsProvider->getRawOptions();

        $store = [];
        foreach ($options as $option) {
            $store[] = [
                $option['key'],
                $option['value'],
            ];
        }

        return $store;
    }
}
