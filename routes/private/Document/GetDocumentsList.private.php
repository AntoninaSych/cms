<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\Document\UI\Controllers\DocumentController;

/** @var Router $router */
$router->get('documents', [
    'as' => 'documents.list',
    'uses' => DocumentController::class . '@list',
    'permissions' => [

    ]
]);
