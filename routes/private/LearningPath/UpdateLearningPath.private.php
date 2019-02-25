<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\LearningPath\UI\Controllers\LearningPathController;

/** @var Router $router */
$router->patch('learnpaths/{id}', [
    'where' => ['id' => '[0-9]+'],
    'as' => 'learnpaths.update',
    'uses' => LearningPathController::class.'@update',
    'permissions' => [

    ]
]);
