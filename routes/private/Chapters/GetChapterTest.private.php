<?php

use Edhub\CMS\Containers\Chapter\UI\Controllers\ChapterController;
use Edhub\CMS\Containers\Chapter\UI\Controllers\ChapterTestController;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->get('courses/{course}/chapters/{chapter}/tests/{test}', [
    'as' => 'courses.chapters.tests.show',
    'uses' => ChapterTestController::class.'@show',
    'permissions' => [

    ]
]);
