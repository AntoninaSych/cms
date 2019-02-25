<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\Course\UI\Controllers\CourseController;

/** @var Router $router */
$router->get('courses/{id}', [
    'where' => ['id' => '[0-9]+'],
    'as' => 'courses.show',
    'uses' => CourseController::class.'@show',
    'permissions' => [

    ]
]);
