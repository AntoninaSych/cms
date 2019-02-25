<?php

use Edhub\CMS\Containers\Chapter\UI\Controllers\ChapterController;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->post('courses/{course}/chapters', [
    'as' => 'courses.chapters.store',
    'uses' => ChapterController::class.'@store',
    'permissions' => [

    ]
]);
