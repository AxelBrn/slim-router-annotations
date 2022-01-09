<?php

namespace RouterAnnotations;

use RouterAnnotations\Utils\RouterReader;
use Slim\App;

class SlimRouterAnnotations
{
    /**
     * @param App $app
     * @param string $scanDir controllers path directory
     * @param bool $prodMode
     * @return void
     * @SuppressWarnings(PHPMD)
     */
    public static function read(App $app, string $scanDir, bool $prodMode = false): void
    {
        if ($prodMode) {
            $reader = new RouterReader();
            $reader->readCache($app);
        } else {
            $reader = new RouterReader($scanDir);
            $reader->generateRoutes($app);
        }
    }
}
