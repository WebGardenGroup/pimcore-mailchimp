<?php

namespace App;

use Pimcore\HttpKernel\BundleCollection\BundleCollection;
use Pimcore\Kernel as BaseKernel;
use Wgg\MailchimpBundle\WggMailchimpBundle;

class Kernel extends BaseKernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundlesToCollection(BundleCollection $collection)
    {
        $collection->addBundle(new WggMailchimpBundle());
    }
}
