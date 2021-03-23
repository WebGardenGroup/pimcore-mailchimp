# mailchimp-bundle

Mailchimp integration for Pimcore

#### Requirements

* Pimcore >= 6.8.0

## Installation

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/WebGardenGroup/mailchimp-bundle.git"
        }
    ],
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

`//TODO`
