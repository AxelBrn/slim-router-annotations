<?php

namespace RouterAnnotations;

use ReflectionClass;
use ReflectionException;
use RouterAnnotations\Utils\ControllerClass;
use Slim\App;

class SlimRouterAnnotations
{
    /**
     * @throws ReflectionException
     */
    public static function read(App $app, string $scanDir): void
    {
        $files = scandir('../'.$scanDir.DIRECTORY_SEPARATOR);
        if (is_array($files)) {
            foreach ($files as $file) {
                if (str_contains($file, '.php')) {
                    $cwd = getcwd();
                    if (!$cwd) {
                        continue;
                    }
                    $parent =  dirname($cwd);
                    require_once($parent.DIRECTORY_SEPARATOR.$scanDir.DIRECTORY_SEPARATOR.$file);
                    $arrayClass = get_declared_classes();
                    $className = end($arrayClass);
                    if (!$className) {
                        continue;
                    }
                    $reflectionClass = new ReflectionClass($className);

                    $controllerClass = new ControllerClass($reflectionClass);
                    $controllerClass->generateRouting($app);
                }
            }
        }
    }
}
