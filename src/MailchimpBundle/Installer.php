<?php

namespace Wgg\MailchimpBundle;

use Pimcore\Db;
use Pimcore\Extension\Bundle\Installer\AbstractInstaller;
use Pimcore\Model\User\Permission\Definition;

class Installer extends AbstractInstaller
{
    const PERMISSIONS = [
        'mailchimp.permission',
    ];

    public function canBeInstalled()
    {
        return true;
    }

    public function canBeUninstalled()
    {
        return true;
    }

    public function canBeUpdated()
    {
        return true;
    }

    public function install(): void
    {
        foreach (self::PERMISSIONS as $permission) {
            $definition = Definition::getByKey($permission);

            if ($definition instanceof Definition) {
                continue;
            }

            Definition::create($permission);
        }
    }

    public function uninstall(): void
    {
        $db = Db::get();

        foreach (self::PERMISSIONS as $permission) {
            $definition = Definition::getByKey($permission);

            if ($definition instanceof Definition) {
                $db->delete(
                    'users_permission_definitions',
                    [
                        '`key`' => $permission,
                    ]
                );
            }
        }
    }
}
