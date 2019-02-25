<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\Course\UI\Controllers\CourseController;

/** @var Router $router */
$router->get('courses', [
    'as' => 'courses.list',
    'uses' => CourseController::class.'@list',
    'permissions' => [

    ]
]);
