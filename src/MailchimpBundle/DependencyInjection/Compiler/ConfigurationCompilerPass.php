<?php

namespace Wgg\MailchimpBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Wgg\MailchimpBundle\MailchimpConfiguration;

class ConfigurationCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $configurationService = $container->getDefinition(MailchimpConfiguration::class);

        /** @var string $storageId */
        $storageId = $container->getParameter('wgg_mailchimp.storage');

        $configurationService
            ->setArguments([
                new Reference($storageId),
            ]);
    }
}
