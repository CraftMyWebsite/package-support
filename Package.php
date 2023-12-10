<?php

namespace CMW\Package\Support;

use CMW\Manager\Package\IPackageConfig;
use CMW\Manager\Package\PackageMenuType;
use CMW\Manager\Package\PackageSubMenuType;

class Package implements IPackageConfig
{
    public function name(): string
    {
        return "Support";
    }

    public function version(): string
    {
        return "0.0.3";
    }

    public function authors(): array
    {
        return ["Zomb"];
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
                lang: "fr",
                icon: "fas fa-ticket",
                title: "Support",
                url: null,
                permission: null,
                subMenus: [
                    new PackageSubMenuType(
                        title: 'Gestion',
                        permission: 'support.todo', //TODO PERMS
                        url: 'support/manage',
                    ),
                    new PackageSubMenuType(
                        title: 'Paramètres',
                        permission: 'support.todo', //TODO PERMS
                        url: 'support/settings',
                    ),
                ]
            ),
            new PackageMenuType(
                lang: "en",
                icon: "fas fa-ticket",
                title: "Support",
                url: null,
                permission: null,
                subMenus: [
                    new PackageSubMenuType(
                        title: 'Manage',
                        permission: 'support.todo', //TODO PERMS
                        url: 'support/manage',
                    ),
                    new PackageSubMenuType(
                        title: 'Settings',
                        permission: 'support.todo', //TODO PERMS
                        url: 'support/settings',
                    ),
                ]
            ),
        ];
    }
}