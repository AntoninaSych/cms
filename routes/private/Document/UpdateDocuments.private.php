<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\Document\UI\Controllers\DocumentController;

/** @var Router $router */
$router->patch('documents/{id}', [
    'where' => ['id' => '[0-9]+'],
    'as' => 'documents.update',
    'uses' => DocumentController::class . '@update',
    'permissions' => [

    ]
]);
