<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Entity;

use Edhub\CMS\Containers\Common\Collections\CompanyIdCollection;
use Edhub\CMS\Containers\Common\Tasks\GetCurrentUserCompaniesTask;
use Edhub\CMS\Containers\Common\Values\Language;
use Edhub\CMS\Containers\Course\Domain\Collections\Courses;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use App\Containers\Media\Services\MediaEntityTrait;
use Edhub\CMS\Containers\Course\Domain\Values\CompanyId;
use Edhub\CMS\Containers\Course\Domain\Values\CourseId;
use Edhub\CMS\Containers\Document\Domain\Collections\DocumentCollection;
use Edhub\CMS\Containers\Document\Domain\Entities\Document;
use App\Containers\Media\Services\TypeFile;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Code;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Description;
use Edhub\CMS\Containers\LearningPath\Domain\Values\LearningPathId;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Meta;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Points;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Status;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Subtitle;
use Illuminate\Database\Eloquent\Model;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Title;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\{HasMedia, HasMediaTrait};
use Spatie\MediaLibrary\Models\Media;

class LearningPath extends Model implements HasMedia
{
    use HasMediaTrait, MediaEntityTrait;
    private const IMAGE_COLLECTION = 'learning_paths';
    protected $table = 'learning_paths';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    public static function create(Title $title)
    {
        $learningPath = new self();
        $learningPath->changeTitle($title);
        $learningPath->changeStatus(Status::draft());

        return $learningPath;
    }

    public function id(): LearningPathId
    {
        return LearningPathId::new((int)$this->getAttribute('id'));
    }

    public function changeMeta(Meta $value)
    {
        $meta = json_encode($value->value());
        if ($meta) {
            $this->setAttribute('meta', $meta);
        }
    }

    public function language(): Language
    {
        return Language::new(
            (string)$this->getAttribute('language')
        );
    }

    public function changeLanguage(Language $language): void
    {
        $this->setAttribute('language', $language->value());
    }

    public function changeTitle(Title $title): void
    {
        $this->setAttribute('title', $title->value());
    }

    public function changeSubtitle(Subtitle $subtitle): void
    {
        $this->setAttribute('subtitle', $subtitle->value());
    }

    public function description(): Description
    {
        $decodedDescription = (array)json_decode($this->getAttribute('description'), true);

        return Description::new($decodedDescription);
    }

    public function changeDescription(Description $description): void
    {
        $encodedDescription = json_encode($description->value());
        if ($encodedDescription) {
            $this->setAttribute('description', $encodedDescription);
        }
    }

    public function changeStatus(Status $status): void
    {
        $this->setAttribute('status', $status->value());
    }

    public function changePoints(Points $points): void
    {
        $this->setAttribute('points', $points->value());
    }

    public function changeCode(Code $code): void
    {
        $this->setAttribute('code', $code->value());
    }

    public function title(): Title
    {
        return Title::new((string)$this->getAttribute('title'));
    }

    public function subtitle(): Subtitle
    {
        return Subtitle::new((string)$this->getAttribute('subtitle'));
    }

    public function status(): Status
    {
        return Status::new((string)$this->getAttribute('status'));
    }

    public function points(): Points
    {
        return Points::new((int)$this->getAttribute('points'));
    }

    public function code(): Code
    {
        return Code::new((string)$this->getAttribute('code'));
    }

    public function meta(): Meta
    {
        $decodedMeta = json_decode($this->getAttribute('meta'), true) ?? [];

        return Meta::new($decodedMeta);
    }

    /** @return Course[] */
    public function courses(): array
    {
        return $this->courses_relation->all();
    }

    public function syncCourses(Courses $courses, GetCurrentUserCompaniesTask $currentUserCompaniesTask)
    {
        $userCompanies = array_map(function (int $companyId) {
            return CompanyId::new($companyId);
        }, $currentUserCompaniesTask->run());
        $availableProvidedCourses = $courses->filter(function (Course $course) use ($userCompanies) {
            $courseCompanies = $course->companies();
            $companiesThatHaveAccessToCourse = array_filter($userCompanies, [$courseCompanies, 'hasAccessToCourse']);
            $isUserHasAccessToCourse = !empty($companiesThatHaveAccessToCourse);

            return $isUserHasAccessToCourse;
        });
        $userAvailableLearningPathCourses = Courses::make($this->courses())->filter(function (Course $course) use ($userCompanies) {
            $courseCompanies = $course->companies();
            $companiesThatHaveAccessToCourse = array_filter($userCompanies, [$courseCompanies, 'hasAccessToCourse']);
            $isUserHasAccessToCourse = !empty($companiesThatHaveAccessToCourse);

            return $isUserHasAccessToCourse;
        });
        $userAvailableLearningPathCoursesIds = array_map(function (Course $course) {
            return $course->id()->value();
        }, $userAvailableLearningPathCourses->getValues());
        $this->courses_relation()->detach($userAvailableLearningPathCoursesIds);

        $position = 1;
        foreach ($availableProvidedCourses as $course) {
            $this->courses_relation()->attach($course, ['position' => $position]);
            $position++;
        }
        $this->load('courses_relation');
    }

    public function courses_relation(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'learning_path_courses','learning_path_id', 'course_id')
            ->withPivot('position');
    }

    public function syncCompanies(GetCurrentUserCompaniesTask $currentUserCompanies, CompanyId...$providedCompaniesList): void
    {
        $currentUserAvailableCompanies = $currentUserCompanies->run();
        $userProvidedAvailableCompanies = array_filter($providedCompaniesList, function (CompanyId $companyId) use ($currentUserAvailableCompanies) {
            return in_array($companyId->value(), $currentUserAvailableCompanies, true);
        });
        $userLearningPathAvailableCompanies = array_filter($this->companies(), function (CompanyId $companyId) use ($currentUserAvailableCompanies) {
            return in_array($companyId->value(), $currentUserAvailableCompanies, true);
        });

        // Remove relations that are related to user available companies of learning path
        $this->companies_relation()
            ->whereIn('company_id', CompanyIdCollection::make($userLearningPathAvailableCompanies)->toScalarList())
            ->delete();

        // Add relations
        $learningPathCompanies = array_map(function (CompanyId $companyId) {
            return LearningPathCompany::create($this->id(), $companyId);
        }, $userProvidedAvailableCompanies);
        $this->companies_relation()->saveMany($learningPathCompanies);

        $this->load('companies_relation');
    }

    /** @return CompanyId[] */
    public function companies(): array
    {
        $companiesList = $this->companies_relation->all();

        $companiesIdList = array_map(function (LearningPathCompany $learningPathCompany) {
            return $learningPathCompany->company();
        }, $companiesList);

        return $companiesIdList;
    }

    public function companies_relation(): HasMany
    {
        return $this->hasMany(LearningPathCompany::class, 'learning_path_id', 'id');
    }

    public function replaceImage(string $base64Path): void
    {
        $this->removeImage();

        if (!empty($base64Path)) {
            preg_match('/image\/(png|jpeg)+/', $base64Path, $matchedType);
            $extension = array_pop($matchedType);
            $fileNameHash = md5('learning_path_image_' . $this->id()->value());

            $this->addMediaFromBase64($base64Path, 'image/jpeg', 'image/png')
                ->withCustomProperties(['mime-type' => "image/$extension"])
                ->usingFileName("$fileNameHash")
                ->toMediaCollection(self::IMAGE_COLLECTION);
        }
    }

    public function images(): array
    {
        $media = $this->getFirstMedia(self::IMAGE_COLLECTION);
        $images = [];
        $typeFile = TypeFile::image();
        foreach ($this->getMediaConversions() as $mediaConversionName) {
            $url = (!empty($media))
                ? $this->getFakeUrl($media->getFullUrl($mediaConversionName), $typeFile)
                : '';
            $images[$mediaConversionName] = $url;
        }

        return $images;
    }

    private function removeImage(): void
    {
        $this->clearMediaCollection(self::IMAGE_COLLECTION);
    }

    public function registerMediaConversions(Media $media = null)
    {
        /*
         * @see https://docs.spatie.be/laravel-medialibrary/v7/converting-images/defining-conversions#using-multiple-conversions
         */
        $this->addMediaConversion('preview')
            ->fit(Manipulations::FIT_MAX, 250, 250)
            ->keepOriginalImageFormat()
            ->performOnCollections(self::IMAGE_COLLECTION);

        $this->addMediaConversion('featured')
            ->keepOriginalImageFormat()
            ->performOnCollections(self::IMAGE_COLLECTION);
    }

    public function wasUpdatedAt(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->getAttribute('updated_at'));
    }

    private function getMediaConversions(): array
    {
        return ['preview', 'featured'];
    }

    public function documents(): DocumentCollection
    {
        $documents = $this->documents_relation->all();
        $documentsCollection = DocumentCollection::make($documents);

         return $documentsCollection;
    }

    /** @deprecated  */
    public function documents_relation()
    {
        return $this->belongsToMany(Document::class,
            'learning_path_documents', 'learning_path_id', 'content_document_id');
    }
}

