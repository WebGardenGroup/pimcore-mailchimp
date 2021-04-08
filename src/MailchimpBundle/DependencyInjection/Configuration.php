<?php

namespace Wgg\MailchimpBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Wgg\MailchimpBundle\Storage\FileStorage;
use Wgg\MailchimpBundle\Storage\SettingsStoreStorage;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('wgg_mailchimp');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->scalarNode('storage')
            ->info(sprintf('Which service to use for storage (available: %s, %s)',
                FileStorage::class,
                SettingsStoreStorage::class))
            ->defaultValue(FileStorage::class)
            ->end()
            ->end();

        return $treeBuilder;
    }
}
