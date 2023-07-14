<?php

namespace CMW\Implementation\Support;

use CMW\Interface\Core\IMenus;

class SupportMenusImplementations implements IMenus {

    public function getRoutes(): array
    {
        return [
            "Support" => 'support'
        ];
    }

    public function getPackageName(): string
    {
        return 'Support';
    }
}