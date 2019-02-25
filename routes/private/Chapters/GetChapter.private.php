<?php

use Edhub\CMS\Containers\Chapter\UI\Controllers\ChapterController;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->get('courses/{course}/chapters/{chapter}', [
    'as' => 'courses.chapters.show',
    'uses' => ChapterController::class.'@show',
    'permissions' => [

    ]
]);
