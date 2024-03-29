## WARNING: This repository is abandoned

There is no active support on it.

Feel free to ask if you want to help to keep this project up to date.

---

<p align="center"><img width="600" alt="pimcore-mailchimp" src="./images/logo_fullcolor.svg?raw=true" /></p>

Mailchimp integration for Pimcore

[![Packagist](https://img.shields.io/packagist/v/wgg/pimcore-mailchimp)](https://packagist.org/packages/wgg/pimcore-mailchimp)
[![Software License](https://img.shields.io/packagist/l/wgg/pimcore-mailchimp)](LICENSE)

#### Requirements

* Pimcore X (^10.0.0)

## Installation

```shell
$ composer require wgg/pimcore-x-mailchimp
```

### Installation via Extension Manager

After you have installed the Mailchimp Bundle via composer, open Pimcore backend and go to `Tools` => `Bundles`:

- Click the green `+` Button in `Enable / Disable` row
- Click the green `+` Button in `Install/Uninstall` row

### Installation via CommandLine

After you have installed the Mailchimp Bundle via composer:

- Execute: `$ bin/console pimcore:bundle:enable WggMailchimpBundle`
- Execute: `$ bin/console pimcore:bundle:install WggMailchimpBundle`

## Upgrading

### Upgrading via Extension Manager

After you have updated the Mailchimp Bundle via composer, open Pimcore backend and go to `Tools` => `Bundles`:

- Click the green `+` Button in `Update` row

### Upgrading via CommandLine

After you have updated the Mailchimp Bundle via composer:

- Execute: `$ bin/console pimcore:bundle:update WggMailchimpBundle`

### Migrate via CommandLine

Does actually the same as the update command and preferred in CI-Workflow:

- Execute: `$ bin/console pimcore:migrations:migrate -b WggMailchimpBundle`

## Bundle configuration

The bundle currently supports two kind of configuration storage:

- [`Wgg\MailchimpBundle\Storage\FileStorage`](Storage/FileStorage.php) - stores config as YAML file
  under `PIMCORE_CONFIGURATION_DIRECTORY`
- [`Wgg\MailchimpBundle\Storage\SettingsStoreStorage`](Storage/SettingsStoreStorage.php) - stores config
  through [`SettingsStore`](https://pimcore.com/docs/pimcore/master/Development_Documentation/Development_Tools_and_Details/Settings_Store.html)

You can configure it:

```yaml
# Use the FileStorage
wgg_mailchimp:
    storage: 'Wgg\MailchimpBundle\Storage\FileStorage' #this is the default
```

```yaml
# Use the SettingsStore
wgg_mailchimp:
    storage: 'Wgg\MailchimpBundle\Storage\SettingsStoreStorage'
```

You can also implement your own storage.

```php
<?php

namespace Acme;

class OwnStorage implements \Wgg\MailchimpBundle\Storage\StorageInterface
{
    public function read(): array
    {
        // You own logic to get the data
        return [];
    }
    
    public function write(string $apiKey, string $serverPrefix, array $listIds): void
    {
        // You own logic to save the data
    }
}
```

```yaml
# Use your own storage implementation
# 1. register your class
services:
    Acme\OwnStorage: ~

# 2. Configure the bundle to use your storage
wgg_mailchimp:
    storage: 'Acme\OwnStorage'
```

## Usage

Configuration is accessible from the `Settings / Mailchimp Settings` on the administration panel.

Through [`Wgg\MailchimpBundle\Util\ApiClient`](Util/ApiClient.php) service you can access all Mailchimp API
functionality.

You can use [`Wgg\MailchimpBundle\Util\ListOptionsProvider`](Util/ListOptionsProvider.php) service in a `Select Type`
for audience/list ids.

From twig you can use `mailchimp_list_store` function to get access to list/audience ids and names.

## Testing configuration

On the admin panel there is a `Validate settings` button - you can use it to validate the configuration.

From cli you can ping the Mailchimp API and get information about the configured lists:

```bash
$ bin/console wg:mailchimp:ping
$ bin/console wg:mailchimp:get-list
```

