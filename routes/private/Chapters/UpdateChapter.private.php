<?php

use Edhub\CMS\Containers\Chapter\UI\Controllers\ChapterController;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->patch('courses/{course}/chapters/{chapter}', [
    'as' => 'courses.chapters.update',
    'uses' => ChapterController::class.'@update',
    'permissions' => [

    ]
]);
