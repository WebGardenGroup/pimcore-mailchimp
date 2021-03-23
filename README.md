# mailchimp-bundle

Mailchimp integration for Pimcore

#### Requirements

* Pimcore >= 6.8.0

## Installation

```json
{
    "require": {
        "wgg/mailchimp-bundle": "dev-master"
    }
}
```

### Installation via Extension Manager

After you have installed the Mailchimp Bundle via composer, open Pimcore backend and go to `Tools` => `Bundles`:

- Click the green `+` Button in `Enable / Disable` row
- Click the green `+` Button in `Install/Uninstall` row

### Installation via CommandLine

After you have installed the Mailchimp Bundle via composer:

- Execute: `$ bin/console pimcore:bundle:enable WgMailchimpBundle`
- Execute: `$ bin/console pimcore:bundle:install WgMailchimpBundle`

## Upgrading

### Upgrading via Extension Manager

After you have updated the Mailchimp Bundle via composer, open Pimcore backend and go to `Tools` => `Bundles`:

- Click the green `+` Button in `Update` row

### Upgrading via CommandLine

After you have updated the Mailchimp Bundle via composer:

- Execute: `$ bin/console pimcore:bundle:update WgMailchimpBundle`

### Migrate via CommandLine

Does actually the same as the update command and preferred in CI-Workflow:

- Execute: `$ bin/console pimcore:migrations:migrate -b WgMailchimpBundle`

## Usage

Configuration is accessible from the `Settings / Mailchimp Settings` on the administration panel.

Through [`Wgg\MailchimpBundle\ApiClient`](src/MailchimpBundle/ApiClient.php) service you can access all Mailchimp API
functionality.

You can use [`Wgg\MailchimpBundle\ListOptionsProvider`](src/MailchimpBundle/ListOptionsProvider.php) service in
a `Select Type` for audience/list ids.

From twig you can use `mailchimp_list_store` function to get access to list/audience ids and names.

## Testing configuration

On the admin panel there is a `Validate settings` button - you can use it to validate the configuration.

From cli you can ping the Mailchimp API and get information about the configured lists:

```bash
$ bin/console wg:mailchimp:ping
$ bin/console wg:mailchimp:get-list
```

