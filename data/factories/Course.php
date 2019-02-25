<?php

use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Edhub\CMS\Containers\Course\Domain\Values\CourseStatus;
use Edhub\CMS\Containers\Common\Values\Language;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Course::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->unique()->randomNumber(3),
        'title' => $faker->jobTitle,
        'subtitle' => $faker->text(100),
        'description' => json_encode([]),
        'status' => CourseStatus::published()->value(),
        'language' => 'en',
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
});
