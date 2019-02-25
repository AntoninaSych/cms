<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\Document\UI\Controllers\DocumentController;

/** @var Router $router */
$router->delete('documents/{id}', [
    'where' => ['id' => '[0-9]+'],
    'as' => 'documents.remove',
    'uses' => DocumentController::class . '@remove',
    'permissions' => [

    ]
]);
