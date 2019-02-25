<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\Document\UI\Controllers\DocumentController;

/** @var Router $router */
$router->get('documents/{id}', [
    'where' => ['id' => '[0-9]+'],
    'as' => 'documents.show',
    'uses' => DocumentController::class . '@show',
    'permissions' => [

    ]
]);
