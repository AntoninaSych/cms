<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\Course\UI\Controllers\CourseController;

/** @var Router $router */
$router->post('courses', [
    'as' => 'courses.store',
    'uses' => CourseController::class.'@store',
    'permissions' => [

    ]
]);
