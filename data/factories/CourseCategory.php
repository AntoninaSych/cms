<?php

use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategory;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(CourseCategory::class, function (Faker\Generator $faker) {
    $jobTitle = $faker->jobTitle;

    return [
        'id' => $faker->unique()->randomNumber(3),
        'title' => [
            'en' => $jobTitle,
            'nl' => $jobTitle
        ],
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
});
