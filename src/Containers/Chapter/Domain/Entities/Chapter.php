<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Entities;

use Edhub\Shared\Assertions\Assertion;
use Edhub\CMS\Containers\Chapter\Domain\Collections\ChapterTestCollection;
use Edhub\CMS\Containers\Chapter\Domain\Collections\OrderedChapterTestCollection;
use Edhub\CMS\Containers\Chapter\Domain\Values\ChapterId;
use Edhub\CMS\Containers\Chapter\Domain\Values\ChapterType;
use Edhub\Shared\Exceptions\InvalidArgumentException;
use Edhub\CMS\Containers\Course\Domain\Collections\Chapters;
use Edhub\CMS\Containers\Course\Domain\Values\CourseId;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Chapter extends Model
{

    use SoftDeletes;

    /**
     * Should be filled with "id" while using NodeTrait to be capable to rebuild a tree.
     * Position is needed to track item position per level.
     * @var array
     */
    protected $fillable = ['id', 'position'];
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $table = 'course_chapters';
    use NodeTrait, Searchable {
        children as public childrenRelation;
        Searchable::usesSoftDelete insteadof NodeTrait;
    }

    public static function create(string $title, CourseId $courseId): Chapter
    {
        $chapter = new self;
        $chapter->changeTitle($title);
        $chapter->belongToCourse($courseId);
        $chapter->makeRoot();
        $chapter->changeType(ChapterType::text());
        $chapter->setAttribute('position', $defaultPosition = 1);

        return $chapter;
    }

    public function course(): CourseId
    {
        return CourseId::new((int)$this->getAttributeValue('course_id'));
    }

    public function id(): ChapterId
    {
        return ChapterId::new(
            (int)$this->getAttributeValue('id')
        );
    }

    public function title(): string
    {
        return (string)$this->getAttributeValue('title');
    }

    /**
     * @param string $title
     * @throws \Assert\AssertionFailedException
     */
    public function changeTitle(string $title): void
    {
        Assertion::notEmpty($title, 'Chapter title is empty.');

        $this->setAttribute('title', $title);
    }

    public function subtitle(): string
    {
        return (string)$this->getAttributeValue('subtitle');
    }

    public function changeSubtitle(string $subtitle): void
    {
        $this->setAttribute('subtitle', $subtitle);
    }

    public function content(): array
    {
        return json_decode($this->getAttribute('content'), true) ?? [];
    }

    public function changeContent(array $content): void
    {
        $encodedContent = json_encode($content, JSON_PRETTY_PRINT);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Chapter content cannot be encoded to JSON.');
        }

        $this->setAttribute('content', $encodedContent);
    }

    public function type(): ChapterType
    {
        return ChapterType::new((int)$this->getAttributeValue('type'));
    }

    public function changeType(ChapterType $type): void
    {
        $this->setAttribute('type', $type->value());
    }

    public function extraLink(): string
    {
        return (string)$this->getAttributeValue('extra_link');
    }

    public function changeExtraLink(string $extraLink): void
    {
        $this->setAttribute('extra_link', $extraLink);
    }

    public function position(): int
    {
        return (int)$this->getAttribute('position');
    }

    public function children(): Chapters
    {
        $childrenChapters = $this->childrenRelation()
            ->orderBy('position')
            ->get()
            ->all();

        return Chapters::make($childrenChapters);
    }

    /**
     * Update presented collection of tests with a new one.
     *
     * @param ChapterTestCollection $chapterTests
     */
    public function updateTestsPosition(ChapterTestCollection $chapterTests): void
    {
        $orderedChapterTests = OrderedChapterTestCollection::make($chapterTests->toArray());
        $chapterTestsArray = iterator_to_array($orderedChapterTests);

        $this->test_relation()->saveMany($chapterTestsArray);
    }

    public function tests(): ChapterTestCollection
    {
        return ChapterTestCollection::make(
            $this->test_relation->all()
        );
    }

    //@TODO impl? We could have temp file in description
    public function addFile($file)
    {
        $mediaCollectionName = "courses/{$this->course()->value()}/paragraphs/{$this->id()->value()}";
        $this
            ->addMedia($file)
            ->toMediaCollection($mediaCollectionName);
        // mediaCollection name should be: courses/{course_id}/paragraphs/{paragraph_id}
    }

    //@TODO impl? We could have temp file in description
    public function removeFile(string $fileName)
    {
        $media = $this->media()->where('name', '=', $fileName)->get();
        //@TODO Remove
    }

    public function addImage(string $base64)
    {
        //@TODO impl? We could have temp image in description
    }

    public function removeImage(string $imageName)
    {
        //@TODo Impl? We could have temp image in description
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function test_relation()
    {
        return $this->hasMany(ChapterTest::class)->orderBy('position');
    }

    public function wasUpdatedAt(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(
            $this->getAttribute('updated_at')
        );
    }

    private function belongToCourse(CourseId $courseId)
    {
        $this->setAttribute('course_id', $courseId->value());
    }

    public static function usesSoftDelete()
    {
        static $softDelete;

        if (is_null($softDelete)) {
            $instance = new static;

            return $softDelete = method_exists($instance, 'bootSoftDeletes');
        }

        return $softDelete;
    }


}