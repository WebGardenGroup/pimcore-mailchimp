<?php

namespace Wgg\MailchimpBundle\Storage;

interface StorageInterface
{
    public function read(): array;

    public function write(string $apiKey, string $serverPrefix, array $listIds): void;
}
