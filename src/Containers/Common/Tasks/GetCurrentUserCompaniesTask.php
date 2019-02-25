<?php


namespace Edhub\CMS\Containers\Common\Tasks;


use Edhub\CMS\Containers\Common\Services\AvailableUserCompanies;
use Illuminate\Contracts\Auth\Guard;

class GetCurrentUserCompaniesTask
{
    private $currentUserId;
    private $availableUserCompanies;

    public function __construct(Guard $guard, AvailableUserCompanies $availableUserCompanies)
    {
        $this->currentUserId = $guard->user()->getAuthIdentifier();
        $this->availableUserCompanies = $availableUserCompanies;
    }

    /** @return int[] */
    public function run(): array
    {
       return $this->availableUserCompanies->getAvailableCompanies($this->currentUserId);
    }
}