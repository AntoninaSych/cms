<?php

use Edhub\CMS\Providers\DefaultCurrentUserCompanyRoleProvider;
use Edhub\CMS\Providers\Laravel\CMSServiceProvider;

return [
    'name' => CMSServiceProvider::MODULE_NAME,
    'title' => 'CMS',
    'providers' => [
        'role' => DefaultCurrentUserCompanyRoleProvider::class,
    ],
];