<?php

namespace Edhub\CMS\Providers\Laravel;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class CMSRouteServiceProvider extends RouteServiceProvider
{
    public function map()
    {
        $this->registerApiRoutes();
    }

    private function registerApiRoutes()
    {
        $privateApiRoutes = __DIR__ . '/../../../routes/private';
        $publicApiRoutes = __DIR__ . '/../../../routes/public';

        $this->registerRoutes($privateApiRoutes, ['api', 'auth.private']);
        $this->registerRoutes($publicApiRoutes, ['api', 'auth.public']);
    }

    private function registerRoutes(string $routeDirectory, array $middlewares): void
    {
        /** @var \SplFileInfo[] $routes */
        $apiRoutes = File::allFiles($routeDirectory);

        foreach ($apiRoutes as $apiRoute) {
            if (File::isFile($apiRoute)) {
                Route::middleware($middlewares)
                    // Add prefix to routes' url
                    ->prefix(CMSServiceProvider::MODULE_NAME)
                    // Add prefix to routes' names
                    ->as(CMSServiceProvider::MODULE_NAME.'.api.')
                    ->group($apiRoute->getPathname());
            }
        }
    }
}