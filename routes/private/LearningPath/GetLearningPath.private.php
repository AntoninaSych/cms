<?php

use Illuminate\Routing\Router;

use Edhub\CMS\Containers\LearningPath\UI\Controllers\LearningPathController;


/** @var Router $router */
$router->get('learnpaths/{id}', [
    'where' => ['id' => '[0-9]+'],
    'as' => 'learnpaths.show',
    'uses' => LearningPathController::class.'@show',
    'permissions' => [

    ]
]);
