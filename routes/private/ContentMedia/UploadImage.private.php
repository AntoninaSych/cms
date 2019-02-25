<?php

use Edhub\CMS\Auth\CmsPermissions;
use Edhub\CMS\Containers\ContentMedia\UI\Controllers\ContentMediaController;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->post('media/images', [
    'as' => 'media.images',
    'uses' => ContentMediaController::class.'@uploadImage',
    'permissions' => [
        CmsPermissions::MANAGE_CONTENT
    ]
]);
