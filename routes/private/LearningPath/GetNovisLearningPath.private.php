<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\LearningPath\UI\Controllers\LearningPathController;

/** @var Router $router */
$router->get('learnpaths/novis', [
    'as' => 'learnpaths.novis',
    'uses' => LearningPathController::class.'@listNovies',
    'permissions' => [

    ]
]);
