<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\CourseCategory\UI\Controllers\Controller;

/** @var Router $router */
$router->patch('courses/categories/{id}', [
    'where' => ['id' => '[0-9]+'],
    'as' => 'courses.categories.update',
    'uses' => Controller::class.'@update',
    'permissions' => [

    ]
]);
