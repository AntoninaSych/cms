<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\CourseCategory\UI\Controllers\Controller;

/** @var Router $router */
$router->get('courses/categories', [
    'as' => 'courses.categories.list',
    'uses' => Controller::class.'@list',
    'permissions' => [

    ]
]);
