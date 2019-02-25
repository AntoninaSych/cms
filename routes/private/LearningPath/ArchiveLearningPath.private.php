<?php

use Edhub\CMS\Containers\LearningPath\UI\Controllers\LearningPathController;

$router->delete('learnpaths/{id}', [
        'where' => ['id' => '[0-9]+'],
        'as' => 'learnpaths.archive',
        'uses' => LearningPathController::class.'@archive',
        'permissions' => [

        ]
]);
