<?php

use App\Containers\Authentication\Data\Repositories\CompanyProvider;
use Edhub\CMS\Containers\Course\Domain\Collections\Categories;
use Edhub\CMS\Containers\Course\Domain\Collections\CourseCompanies;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Edhub\CMS\Containers\Course\Domain\Entities\CourseCompany;
use Edhub\CMS\Containers\Course\Domain\Values\CompanyId;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategory;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategoryRepository;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Database\Seeder;

class CMSDatabaseSeeder extends Seeder
{
    public function run(Factory $factory, CompanyProvider $companies, CourseCategoryRepository $courseCategories)
    {
        $categoriesAlreadyPresented = $courseCategories->all();
        if (!empty($categoriesAlreadyPresented)) {
            $this->command->getOutput()->comment('CMS seeder is not running because CMS data is already existed.');
            return;
        }

        try {
            $rootCompany = $companies->getRootCompany();
            $categories = $factory->of(CourseCategory::class)->times(3)->create();
            $factory->of(Course::class)
                ->times(3)
                ->create()
                ->each(function (Course $course) use ($categories, $rootCompany) {
//                    $companyId = CompanyId::new($rootCompany->id);
                    $course->changeCategories(new Categories($categories->all()));
//                    $course->assignCompanies(
//                        new CourseCompanies([CourseCompany::create($course->id(), $companyId, true)])
//                    );
                });
        } catch (\Throwable $exception) {
            $this->command->getOutput()->warning($exception->getMessage());
        }
    }
}