<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\CourseCategory\UI\Controllers\Controller;

/** @var Router $router */
$router->get('courses/categories/{id}', [
    'where' => ['id' => '[0-9]+'],
    'as' => 'courses.categories.show',
    'uses' => Controller::class.'@show',
    'permissions' => [

    ]
]);
