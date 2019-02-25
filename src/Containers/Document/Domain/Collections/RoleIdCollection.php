<?php


namespace Edhub\CMS\Containers\Document\Domain\Collections;


use Edhub\Shared\Assertions\Assertion;
use Doctrine\Common\Collections\ArrayCollection;
use Edhub\CMS\Containers\Document\Domain\Values\RoleId;

class RoleIdCollection extends ArrayCollection
{
    public function __construct(array $elements = [])
    {
        Assertion::allIsInstanceOf($elements, RoleId::class);
        parent::__construct($elements);
    }

    public static function make(array $roles = []): RoleIdCollection
    {
        return new static($roles);
    }

    public function toScalarList(): array
    {
        $roleItems = array_map(function (RoleId $roleId) {
            return $roleId->value();
        }, $this->toArray());

        return $roleItems;
    }
}