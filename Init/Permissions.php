<?php

namespace CMW\Permissions\Support;

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Permission\IPermissionInit;
use CMW\Manager\Permission\PermissionInitType;

class Permissions implements IPermissionInit
{
    public function permissions(): array
    {
        return [
            new PermissionInitType(
                code: 'support.show',
                description: LangManager::translate('support.permissions.support.show'),
            ),
            new PermissionInitType(
                code: 'support.settings',
                description: LangManager::translate('support.permissions.support.settings'),
            ),

        ];
    }

}