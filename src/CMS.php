<?php

namespace Edhub\CMS;

use App\Ship\Parents\Transformers\FlatArraySerializer;
use Edhub\Shared\Collections\PaginatedCollection;
use Edhub\CMS\Containers\Course\Application\Actions\GetCourse\{GetCourseAction, GetCourseTransporter};
use Edhub\CMS\Containers\Course\Application\Actions\GetCourseList\{GetCourseListAction, GetCourseListTransporter};
use Edhub\CMS\Containers\Course\Application\Actions\SearchCourseContent\SearchCourseContentAction;
use Edhub\CMS\Containers\Course\Application\Actions\SearchCourseContent\SearchCourseContentTransporter;
use Edhub\CMS\Containers\Course\Application\Transporters\CourseTransporter;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Edhub\CMS\Containers\Course\Domain\Exceptions\CompanyHasNoAccessToCourse;
use Edhub\CMS\Containers\Common\Criteria\StatusCriteria;
use Edhub\CMS\Containers\Course\Domain\Values\CompanyId;
use Edhub\CMS\Containers\Course\UI\Transformers\CourseTransformer;
use Edhub\CMS\Containers\CourseCategory\Application\Actions\GetCourseCategoryList\{GetCourseCategoryListAction, GetCourseCategoryListTransporter};
use Edhub\CMS\Containers\CourseCategory\Application\Transporters\CourseCategoryTransporter;
use Edhub\CMS\Containers\CourseCategory\UI\Transformers\CourseCategoryTransformer;
use Edhub\CMS\Containers\Document\Domain\Entities\Document;
use Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList\{GetLearningPathListAction, GetLearningPathListTransporter};
use Edhub\CMS\Containers\LearningPath\Application\Actions\ShowLearningPath\{GetLearningPathAction, GetLearningPathTransporter};
use Edhub\CMS\Containers\LearningPath\Application\Transporters\LearningPathTransporter;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Status;
use Edhub\CMS\CmsLearningPathTransformer as LearningPathTransformer;
use Edhub\CMS\Providers\CurrentUserCompanyRoleProvider;
use League\Fractal\{Manager, Resource\Collection, Resource\Item};
use Psr\Container\ContainerInterface;

class CMS
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var Manager
     */
    private $transformer;

    public function __construct(ContainerInterface $container, Manager $transformer)
    {
        $this->container = $container;
        $this->transformer = $transformer;
    }

    /** @return CourseCategoryTransporter[] */
    public function getCourseCategories(): array
    {
        /** @var GetCourseCategoryListAction $getCourseCategoryListAction */
        $getCourseCategoryListAction = $this->container->get(GetCourseCategoryListAction::class);
        $courseCategoryList = $getCourseCategoryListAction->run(new GetCourseCategoryListTransporter([
            'page' => 1,
            'perPage' => 9999
        ]));

        $transformedCategories = $this->transformer
            ->createData(new Collection($courseCategoryList, new CourseCategoryTransformer()))
            ->toArray()
            ['data'];

        $courseCategoryTransporterList = array_map(function (array $courseCategory) {
            return new CourseCategoryTransporter($courseCategory);
        }, $transformedCategories);

        return $courseCategoryTransporterList;
    }

    public function getCourse(int $id, int $companyId): CourseTransporter
    {
        /** @var GetCourseAction $getCourseAction */
        $getCourseAction = $this->container->get(GetCourseAction::class);
        $course = $getCourseAction->run(new GetCourseTransporter(compact('id')));

        if (!$course->companies()->hasPublicAccessToCourse(CompanyId::new($companyId))) {
            throw new CompanyHasNoAccessToCourse($id, $companyId);
        }

        //@TODO Shared: should be moved to library and update namespace.
        $courseTransporter = $this->createCourseTransporters($course);

        return $courseTransporter;
    }

    //@TODO Create DTO for params
    /** @return CourseTransporter[]|PaginatedCollection */
    public function paginateCourses(array $params = []): PaginatedCollection
    {
        /** @var GetCourseListAction $getCourseListAction */
        $getCourseListAction = $this->container->get(GetCourseListAction::class);

        $courseList = $getCourseListAction->run(new GetCourseListTransporter([
            'page' => $params['page'] ?? 1,
            'perPage' => $params['perPage'] ?? null,
            'filters' => $params['filters'] ?? []
        ]));
        $courseTransportersList = array_map([$this, 'createCourseTransporters'], iterator_to_array($courseList));

        return new PaginatedCollection(
            $courseTransportersList,
            $courseList->total(),
            $courseList->perPage()
        );
    }

    public function getLearningPath(int $id, array $filters = []): LearningPathTransporter
    {
        /** @var CurrentUserCompanyRoleProvider $roleProvider */
        $roleProvider = $this->container->get(CurrentUserCompanyRoleProvider::class);
        $currentUserRoles = $roleProvider->getRoles();
        // Restrict learning path documents list to current user roles
        $filters[] = [
            'name' => 'document.roles',
            'value' => $currentUserRoles
        ];
        
        /** @var GetLearningPathAction $getLearningPathAction */
        $getLearningPathAction = $this->container->get(GetLearningPathAction::class);
        $learningPath = $getLearningPathAction->run(new GetLearningPathTransporter(compact('id', 'filters')));
        $learningPathTransporter = $this->transformLearningPathToTransporter($learningPath);

        return $learningPathTransporter;
    }

    //@TODO Create DTO for params
    /** @return LearningPathTransporter[]|PaginatedCollection */
    public function getLearningPathsList(array $params = []): PaginatedCollection
    {
        $filters = $params['filters'] ?? [];
        // Restrict access to load only published learning paths.
        $filters[] = [
            'name' => StatusCriteria::NAME,
            'value' => [Status::published()->value()]
        ];

        /** @var GetLearningPathListAction $getLearningPathListAction */
        $getLearningPathListAction = $this->container->get(GetLearningPathListAction::class);
        $learningPathList = $getLearningPathListAction->run(new GetLearningPathListTransporter([
            'page' => $params['page'] ?? 1,
            'perPage' => $params['perPage'] ?? 9999,
            'filters' => $filters,
        ]));

        $transformedLearningPathList = array_map(
            [$this, 'transformLearningPathToTransporter'],
            iterator_to_array($learningPathList)
        );

        return new PaginatedCollection(
            $transformedLearningPathList,
            $learningPathList->total(),
            $learningPathList->perPage()
        );
    }

    public function searchCourseContent(int $courseId, string $query = ''): array
    {
        /** @var SearchCourseContentAction $searchCourseContentAction */
        $searchCourseContentAction = $this->container->get(SearchCourseContentAction::class);
        $searchResult = $searchCourseContentAction->run(new SearchCourseContentTransporter(['course' => $courseId, 'query' => $query]));

        return $searchResult;
    }

    private function transformLearningPathToTransporter(LearningPath $learningPath): LearningPathTransporter
    {
        $learningPathTransformer = new LearningPathTransformer();
        $resource = new Item($learningPath, $learningPathTransformer);

        $transformedLearningPath = $this->transformer
            ->parseIncludes(['courses', 'documents'])
            ->setSerializer(new FlatArraySerializer())
            ->createData($resource)
            ->toArray();

        return new LearningPathTransporter($transformedLearningPath);
    }

    private function createCourseTransporters(Course $course): CourseTransporter
    {
        //@TODO Shared: should be moved to library and update namespace.
        $transformedCourses = $this->transformer
            ->setSerializer(new FlatArraySerializer())
            ->parseIncludes(['chapters.children', 'chapters.tests', 'chapters.children.tests'])
            ->createData(new Item($course, new CourseTransformer()))
            ->toArray();

        return new CourseTransporter($transformedCourses);
    }
}