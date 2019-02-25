<?php


namespace Edhub\CMS\Containers\Common\Services;


interface AvailableUserCompanies
{
    public function getAvailableCompanies(int $userId): array;
}