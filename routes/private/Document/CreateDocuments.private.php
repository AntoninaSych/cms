<?php

use Illuminate\Routing\Router;
use Edhub\CMS\Containers\Document\UI\Controllers\DocumentController;

/** @var Router $router */
$router->post('documents', [
    'as' => 'documents.store',
    'uses' => DocumentController::class.'@store',
    'permissions' => [

    ]
]);
