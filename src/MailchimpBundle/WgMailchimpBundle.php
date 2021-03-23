<?php

namespace Wgg\MailchimpBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class WgMailchimpBundle extends AbstractPimcoreBundle
{
    const CACHE_TAG = 'wg_mailchimp_lists';

    public function getJsPaths()
    {
        return [
            '/bundles/wgmailchimp/js/pimcore/startup.js',
            '/bundles/wgmailchimp/js/pimcore/panel.js',
        ];
    }

    public function getCssPaths()
    {
        return [
            '/bundles/wgmailchimp/css/icons.css',
        ];
    }

    public function getInstaller()
    {
        return new Installer();
    }
}
