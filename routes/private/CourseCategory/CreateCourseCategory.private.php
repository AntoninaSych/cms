<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\CourseCategory\UI\Controllers\Controller;

/** @var Router $router */
$router->post('courses/categories', [
    'as' => 'courses.categories.store',
    'uses' => Controller::class.'@store',
    'permissions' => [

    ]
]);
