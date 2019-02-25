<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\LearningPath\UI\Controllers\LearningPathController;


/** @var Router $router */
$router->post('learnpaths', [
    'as' => 'learnpaths.store',
    'uses' => LearningPathController::class . '@store',
    'permissions' => [
    ]
]);
