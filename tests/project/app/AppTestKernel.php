<?php

use Pimcore\HttpKernel\BundleCollection\BundleCollection;
use Pimcore\Kernel;
use Wgg\MailchimpBundle\WggMailchimpBundle;

class AppTestKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundlesToCollection(BundleCollection $collection)
    {
        $collection->addBundle(new WggMailchimpBundle());
    }
}
