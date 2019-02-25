<?php


use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use \Edhub\CMS\Containers\Chapter\Domain\Entities\Chapter;
use \Edhub\CMS\Containers\Course\Domain\Values\CourseId;
use  Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapter\CreateChapterAction;
use Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapter\CreateChapterTransporter;
use \Edhub\CMS\Containers\Chapter\Domain\Values\ChapterType;
use Edhub\CMS\Containers\Chapter\Domain\Repositories\ChapterRepository;
use Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapterTest\CreateChapterTestAction;
use Edhub\CMS\Containers\Chapter\Domain\Repositories\ChapterTestRepository;
use Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapterTest\CreateChapterTestTransporter;
use Edhub\CMS\Containers\Chapter\Domain\Task\CreateChapterTestAnswerTask;
use \Edhub\CMS\Containers\Chapter\Domain\Values\TestType;
use Edhub\CMS\Containers\Course\Application\Actions\UpdateCourse\UpdateCourseAction;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategoryRepository;
use Edhub\CMS\Containers\Course\Domain\Repositories\CourseRepository;
use Illuminate\Container\Container as Application;
use Psr\Log\LoggerInterface;
use \Edhub\CMS\Containers\Course\Application\Actions\UpdateCourse\UpdateCourseTransporter;

class FillExistedCoursesSeeder extends Seeder
{
    public function run(Course $courseEntity, Faker $faker, LoggerInterface $logger, CourseCategoryRepository $categories)
    {
        $courses = $courseEntity->get();

        foreach ($courses as $course) {
            $chaptersCount = $course->chapter_relation()->count();
            if ($chaptersCount === 0) {
                $chapters[] = Chapter::create($faker->paragraph, CourseId::new($course['id']));
                $chapterQuantity = 0;
                $transporterArrayChildren = [];
                while ($chapterQuantity < 6) {

                    $transporterChapter = new CreateChapterTransporter(
                        [
                            'course' => (int)$course['id'],
                            'title' => $faker->realText(191),
                            'subtitle' => $faker->realText(191),
                            'type' => $faker->randomElement([1, 2, 3, 4, 5, 6]),
                            'content' => [["insert" => $faker->realText(5000)]],
                            'extraLink' => $faker->url
                        ]
                    );


                    $chaptersAction = new CreateChapterAction(new ChapterRepository());
                    $chaptersAction = $chaptersAction->run($transporterChapter);

                    $transporterArrayChildren[] = [
                        'id' => $chaptersAction['id'],
                         'children' => []
                    ];

                    if ($chapterQuantity === 3) {
                        $this->addChildren($transporterArrayChildren, $courseEntity, $logger, $categories, $course);
                    }
                   $chapterQuantity++;
                }
            }
            $this->createCourseWithSelfTestSingleAnswer($course, $faker);

        }
    }

    public function addChildren($transporterArrayChildren, Course $courseEntity, $logger, $categories, $course ):void
    {
        $courses = new CourseRepository(new Application(), $logger);
        $actionCourses = new UpdateCourseAction($courses, $categories);
        $currentCourse = $courseEntity->select()->where('id', (int)$course['id'])->first();
        $company = $currentCourse->companies()->toArray();

        $companies = array_map(function ($companyItem) {
            return ['company' => $companyItem['company_id'], 'isPublic' => $companyItem['is_public']];
        }, $company);
        $chapterInfo[] = array_pop($transporterArrayChildren);
        $chapterInfo[0]['children'] = $transporterArrayChildren;

        $updateCourse = [
            "id" => (int)$course['id'],
            "title" => $course['title'],
            "subtitle" => $course['subtitle'],
            "description" => $course['description'],
            "status" => (int)$course['status'],
            "language" => $course['language'],
            'companies' => $companies,
            'chapters' => $chapterInfo
        ];

        $transporter = new UpdateCourseTransporter ($updateCourse);

        $actionCourses->run($transporter);
}

    public function createCourseWithSelfTestSingleAnswer(Course $course, Faker $faker): void
    {

        $transporter = new CreateChapterTransporter(
            [
                'course' => (int)$course['id'],
                'title' => $faker->realText(191),
                'subtitle' => $faker->realText(191),
                'type' => ChapterType::selfTest()->value(),
                'content' => $faker->realText(1000),
                'extraLink' => $faker->url
            ]
        );

        $chaptersAction = new CreateChapterAction(new ChapterRepository());
        $chapterSelfTest = $chaptersAction->run($transporter);

        $actionChapterTest = new CreateChapterTestAction(new ChapterTestRepository(),
            new CreateChapterTestAnswerTask());
        $chapterQuantity = 0;
        while ($chapterQuantity < 3) {
            $chapterQuantity++;
            $transporter = new CreateChapterTestTransporter([
                'course' => (int)$course['id'],
                'chapter' => $chapterSelfTest->id()->value(),
                'title' => $faker->realText(191),
                'type' => TestType::singleAnswer()->value(),
                'correctAnswer' => $faker->realText(191),
                'incorrectAnswer' => $faker->realText(191),
                'answers' => [
                    [
                        'answer' => $faker->realText(191),
                        'isCorrect' => false
                    ],
                    [
                        'answer' => $faker->realText(191),
                        'isCorrect' => false
                    ],
                    [
                        'answer' => $faker->realText(191),
                        'isCorrect' => false
                    ],
                    [
                        'answer' => $faker->realText(191),
                        'isCorrect' => true
                    ],
                ]
            ]);
            $actionChapterTest->run($transporter);
        }
    }
}
