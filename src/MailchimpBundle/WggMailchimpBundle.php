<?php

namespace Wgg\MailchimpBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class WggMailchimpBundle extends AbstractPimcoreBundle
{
    const CACHE_TAG = 'wgg_mailchimp';

    public function getJsPaths()
    {
        return [
            '/bundles/wggmailchimp/js/pimcore/startup.js',
            '/bundles/wggmailchimp/js/pimcore/panel.js',
        ];
    }

    public function getCssPaths()
    {
        return [
            '/bundles/wggmailchimp/css/icons.css',
        ];
    }

    public function getInstaller()
    {
        return new Installer();
    }
}
