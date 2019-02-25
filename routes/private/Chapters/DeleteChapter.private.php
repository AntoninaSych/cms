<?php

use Edhub\CMS\Containers\Chapter\UI\Controllers\ChapterController;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->delete('courses/{course}/chapters/{chapter}', [
    'as' => 'courses.chapters.delete',
    'uses' => ChapterController::class.'@delete',
    'permissions' => [

    ]
]);
