<?php

namespace Wgg\MailchimpBundle\Tests;

use Wgg\MailchimpBundle\MailchimpConfiguration;
use PHPUnit\Framework\TestCase;

class MailchimpConfigurationTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        define('PIMCORE_CONFIGURATION_DIRECTORY', __DIR__.'/tmp');
        define('APP_ENV', 'dev');
    }

    public function testReadConfig()
    {
    }

    public function testWriteConfig()
    {
    }
}
