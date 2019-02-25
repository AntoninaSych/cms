<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\Course\UI\Controllers\CourseController;

/** @var Router $router */
$router->get('courses/statuses', [
    'as' => 'courses.statuses.list',
    'uses' => CourseController::class.'@statuses',
    'permissions' => [

    ]
]);
