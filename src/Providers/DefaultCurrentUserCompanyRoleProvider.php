<?php

namespace Edhub\CMS\Providers;

class DefaultCurrentUserCompanyRoleProvider implements CurrentUserCompanyRoleProvider
{
    public function getRoles(): array
    {
        return [];
    }
}