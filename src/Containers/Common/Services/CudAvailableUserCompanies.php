<?php


namespace Edhub\CMS\Containers\Common\Services;


use Edhub\CUD\CUD;

class CudAvailableUserCompanies implements AvailableUserCompanies
{
    private $cud;

    public function __construct(CUD $cud)
    {
        $this->cud = $cud;
    }

    /**
     * @param int $userId
     * @return int[]
     * @throws \Edhub\CUD\Containers\User\Domain\Exceptions\UserNotFound
     */
    public function getAvailableCompanies(int $userId): array
    {
        $availableCompanies = $this->cud->getUserAvailableCompanies($userId);
        $companyIds = array_column($availableCompanies, 'id');

        return $companyIds;
    }
}