<?php

namespace CMW\Package\Support;

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\IPackageConfig;
use CMW\Manager\Package\PackageMenuType;
use CMW\Manager\Package\PackageSubMenuType;

class Package implements IPackageConfig
{
    public function name(): string
    {
        return 'Support';
    }

    public function version(): string
    {
        return '1.0.0';
    }

    public function authors(): array
    {
        return ['Zomb'];
    }

    public function isGame(): bool
    {
        return false;
    }

    public function isCore(): bool
    {
        return false;
    }

    public function menus(): ?array
    {
        return [
            new PackageMenuType(
                icon: 'fas fa-ticket',
                title: 'Support',
                url: null,
                permission: null,
                subMenus: [
                    new PackageSubMenuType(
                        title: LangManager::translate('support.menu.manage'),
                        permission: 'support.show',
                        url: 'support/manage',
                        subMenus: []
                    ),
                    new PackageSubMenuType(
                        title: LangManager::translate('support.menu.settings'),
                        permission: 'support.settings',
                        url: 'support/settings',
                        subMenus: []
                    ),
                ]
            ),
        ];
    }

    public function requiredPackages(): array
    {
        return ['Core'];
    }

    public function uninstall(): bool
    {
        // Return true, we don't need other operations for uninstall.
        return true;
    }
}
