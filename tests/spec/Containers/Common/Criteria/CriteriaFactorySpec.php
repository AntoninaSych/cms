<?php

namespace spec\Edhub\CMS\Containers\Common\Criteria;
use Edhub\Shared\Criteria\Criteria;
use Edhub\Shared\Criteria\CriteriaFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CriteriaFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CriteriaFactory::class);
    }

    public function it_should_register_criteria(Criteria $criteria)
    {
        $criteriaName = 'test';

        $this->addCriteria($criteriaName, function () use ($criteria) {
            return $criteria;
        });

        $this->hasCriteria($criteriaName)->shouldBe(true);
    }

    public function it_should_resolve_registered_criteria_with_provided_arguments(Criteria $criteria)
    {
        $criteriaName = 'test';

        $this->addCriteria($criteriaName, function (array $arguments) use ($criteria) {
            return $criteria->getWrappedObject();
        });

        $this->create($criteriaName, [])->shouldBe($criteria);
    }
}
