<?php

use Edhub\CMS\Containers\Chapter\UI\Controllers\ChapterController;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->get('courses/chapters/types', [
    'as' => 'courses.chapters.types.list',
    'uses' => ChapterController::class.'@types',
    'permissions' => [

    ]
]);
