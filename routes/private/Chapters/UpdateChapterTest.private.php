<?php

use Edhub\CMS\Containers\Chapter\UI\Controllers\ChapterController;
use Edhub\CMS\Containers\Chapter\UI\Controllers\ChapterTestController;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->patch('courses/{course}/chapters/{chapter}/tests/{test}', [
    'as' => 'courses.chapters.tests.update',
    'uses' => ChapterTestController::class.'@update',
    'permissions' => [

    ]
]);
