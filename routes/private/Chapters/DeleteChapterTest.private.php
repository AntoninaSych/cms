<?php

use Edhub\CMS\Containers\Chapter\UI\Controllers\ChapterController;
use Edhub\CMS\Containers\Chapter\UI\Controllers\ChapterTestController;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->delete('courses/{course}/chapters/{chapter}/tests/{test}', [
    'as' => 'courses.chapters.tests.delete',
    'uses' => ChapterTestController::class.'@delete',
    'permissions' => [

    ]
]);
