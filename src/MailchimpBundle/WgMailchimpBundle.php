<?php

namespace Wg\MailchimpBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class WgMailchimpBundle extends AbstractPimcoreBundle
{
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
