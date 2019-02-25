<?php

namespace Edhub\CMS\Containers\Course\Domain\Entities;

//@TODO Update this dependency. using @Shared
use App\Containers\Media\Services\MediaEntityTrait;
use App\Containers\Media\Services\TypeFile;

use Edhub\CMS\Containers\Chapter\Domain\Entities\Chapter;
use Edhub\Shared\Exceptions\InvalidArgumentException;
use Edhub\CMS\Containers\Common\Tasks\GetCurrentUserCompaniesTask;
use Edhub\CMS\Containers\Course\Domain\Collections\Categories;
use Edhub\CMS\Containers\Course\Domain\Collections\CourseCompanies;
use Edhub\CMS\Containers\Course\Domain\Exceptions\InvalidCourseCompany;
use Edhub\CMS\Containers\Course\Domain\Values\CourseId;
use Edhub\CMS\Containers\Course\Domain\Values\CourseStatus;
use Edhub\CMS\Containers\Common\Values\Language;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategory;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Course extends Model implements HasMedia
{
    use HasMediaTrait, MediaEntityTrait;

    private const IMAGE_COLLECTION = 'courses';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public static function create(string $title, Language $language): Course
    {
        $course = new self;
        $course->changeTitle($title);
        $course->changeLanguage($language);

        return $course;
    }

    public function id(): CourseId
    {
        return CourseId::new(
            (int)$this->getAttribute('id')
        );
    }

    public function title(): string
    {
        return $this->getAttribute('title');
    }

    public function changeTitle(string $title): void
    {
        $this->setAttribute('title', $title);
    }

    public function description(): array
    {
        return json_decode($this->getAttribute('description'), true) ?? [];
    }

    public function changeDescription(array $description): void
    {
        $encodedDescription = json_encode($description, JSON_PRETTY_PRINT);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Description cannot be encoded to JSON. Check its content.');
        }

        $this->setAttribute('description', $encodedDescription);
    }

    public function subtitle(): string
    {
        return $this->getAttribute('subtitle');
    }

    /**
     * @param string $subTitle
     */
    public function changeSubtitle(string $subTitle): void
    {
        $this->setAttribute('subtitle', $subTitle);
    }

    public function changeStatus(CourseStatus $status): void
    {
        $this->setAttribute('status', $status->value());
    }

    public function status(): CourseStatus
    {
        return CourseStatus::new((int)$this->getAttribute('status'));
    }

    public function categories(): Categories
    {
        return new Categories($this->categories_relation->all());
    }

    public function changeCategories(Categories $categories): void
    {
        $ids = array_map(function (CourseCategory $category) {
            return $category->id();
        }, $categories->toArray());

        $this->categories_relation()->sync($ids);
    }

    //@TODO Refactor because we dont know what structure $chapters should have
    public function rebuildChapters(array $chapters): void
    {
        Chapter::query()
            ->where('course_id', '=', $this->id()->value())
            ->rebuildTree($chapters, $deleteNotPresentedChapters = true);
    }

    /**
     * Return 1st-level(parent) chapters only in order to load each chapter's children later.
     *
     * @return Chapter[]|array
     */
    public function chapters(): array
    {
        return $this->chapter_relation()
            ->whereNull('parent_id')
            ->orderBy('position')
            ->get()
            ->all();
    }

    /**
     * Relation method should be kept separately.
     * Mark as deprecated in order to prevent using it by other devs.
     *
     * @deprecated
     */
    public function chapter_relation()
    {
        return $this->hasMany(Chapter::class);
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

    public function companies(): CourseCompanies
    {
        return new CourseCompanies(
            $this->companies_relation->all()
        );
    }

    public function assignCompanies(CourseCompanies $courseCompanies, GetCurrentUserCompaniesTask $task): void
    {
        $availableCompanies = $task->run();

        foreach ($courseCompanies as $company) {
            $id = $company->getAttribute('company_id');
            if (!in_array($id, $availableCompanies, true)) {
                throw new InvalidArgumentException('Please, check your companies related to current user!');
            }
        }


        if (!$courseCompanies->count()) {
            throw new InvalidCourseCompany('Course should belong to at least 1 company.');
        }
        $this->companies_relation()->delete();
        $this->companies_relation()->saveMany(
            $courseCompanies->toArray()
        );
    }

    public function wasUpdatedAt(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(
            $this->getAttribute('updated_at')
        );
    }

    /**
     * Relation method should be kept separately.
     * Mark as deprecated in order to prevent using it by other devs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @deprecated
     */
    public function categories_relation()
    {
        return $this->belongsToMany(CourseCategory::class, 'courses_categories_pivot', 'course_id', 'course_category_id');
    }

    /**
     * Relation method should be kept separately.
     * Mark as deprecated in order to prevent using it by other devs.
     *
     * @deprecated
     */
    public function companies_relation(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseCompany::class);
    }

    public function paths(): array
    {
        return $this->paths_relation->all();
    }

    public function paths_relation()
    {
        return $this->belongsToMany(LearningPath::class, 'learning_path_courses');
    }

    public function replaceImage(string $base64Path): void
    {
        $this->removeImage();

        if (!empty($base64Path)) {
            preg_match('/image\/(png|jpeg)+/', $base64Path, $matchedType);
            $extension = array_pop($matchedType);
            $fileNameHash = md5('courses_feature_image_' . $this->id()->value());

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
        $fileType = TypeFile::image();
        foreach ($this->getMediaConversions() as $mediaConversionName) {
            $url = (!empty($media))
                ? $this->getFakeUrl($media->getFullUrl($mediaConversionName), $fileType)
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

    private function getMediaConversions(): array
    {
        return ['preview', 'featured'];
    }
}