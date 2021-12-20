<?php

namespace RouterAnnotations;

use RouterAnnotations\Utils\RouterReader;
use Slim\App;

class SlimRouterAnnotations
{
    /**
     * @param App $app
     * @param string $scanDir controllers path directory
     * @return void
     */
    public static function read(App $app, string $scanDir): void
    {
        $reader = new RouterReader($scanDir);
        $reader->generateRoutes($app);
    }

}
