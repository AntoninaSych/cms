<?php

use Edhub\CMS\Auth\CmsPermissions;
use Edhub\CMS\Containers\ContentMedia\UI\Controllers\ContentMediaController;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->post('media/files', [
    'as' => 'media.files',
    'uses' => ContentMediaController::class.'@uploadFile',
    'permissions' => [
        CmsPermissions::MANAGE_CONTENT,
    ]
]);
