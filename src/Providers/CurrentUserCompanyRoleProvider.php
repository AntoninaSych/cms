<?php

namespace Edhub\CMS\Providers;


interface CurrentUserCompanyRoleProvider
{
    /** @return int[] */
    public function getRoles(): array;
}