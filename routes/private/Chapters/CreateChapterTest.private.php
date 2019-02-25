<?php

use Edhub\CMS\Containers\Chapter\UI\Controllers\ChapterController;
use Edhub\CMS\Containers\Chapter\UI\Controllers\ChapterTestController;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->post('courses/{course}/chapters/{chapter}/tests', [
    'as' => 'courses.chapters.tests.store',
    'uses' => ChapterTestController::class.'@store',
    'permissions' => [

    ]
]);
