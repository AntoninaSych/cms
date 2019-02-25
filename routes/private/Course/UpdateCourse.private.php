<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\Course\UI\Controllers\CourseController;

/** @var Router $router */
$router->patch('courses/{id}', [
    'where' => ['id' => '[0-9]+'],
    'as' => 'courses.update',
    'uses' => CourseController::class.'@update',
    'permissions' => [

    ]
]);
