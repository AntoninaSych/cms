<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\Course\UI\Controllers\CourseController;

/** @var Router $router */
$router->delete('courses/{id}', [
    'as' => 'courses.delete',
    'uses' => CourseController::class.'@delete',
    'permissions' => [

    ]
]);
