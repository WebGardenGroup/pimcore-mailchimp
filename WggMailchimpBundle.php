<?php

namespace Wgg\MailchimpBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Installer\InstallerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wgg\MailchimpBundle\DependencyInjection\Compiler\ConfigurationCompilerPass;

class WggMailchimpBundle extends AbstractPimcoreBundle
{
    const CACHE_TAG = 'wgg_mailchimp';

    public function getJsPaths(): array
    {
        return [
            '/bundles/wggmailchimp/js/pimcore/startup.js',
            '/bundles/wggmailchimp/js/pimcore/panel.js',
        ];
    }

    public function getCssPaths(): array
    {
        return [
            '/bundles/wggmailchimp/css/icons.css',
        ];
    }

    public function getInstaller(): InstallerInterface
    {
        return new Installer();
    }

    public function build(ContainerBuilder $container): void
    {
        $container
            ->addCompilerPass(new ConfigurationCompilerPass());
    }
}
