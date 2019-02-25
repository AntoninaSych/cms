<?php

use Illuminate\Routing\Router;

use Edhub\CMS\Containers\LearningPath\UI\Controllers\LearningPathController;


/** @var Router $router */
$router->get('learnpaths', [
    'as' => 'learnpaths.list',
    'uses' => LearningPathController::class . '@list',
    'permissions' => [
    ]
]);
