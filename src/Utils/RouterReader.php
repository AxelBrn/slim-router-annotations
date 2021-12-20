<?php

namespace RouterAnnotations\Utils;

use ReflectionClass;
use ReflectionException;
use RouterAnnotations\Models\ControllerModel;
use Slim\App;

class RouterReader
{

    /**
     * @var ControllerClass[] $controllerClasses
     */
    private array $controllerClasses;

    public function __construct(string $dir)
    {
        try {
            $this->controllerClasses = $this->generateControllerClass($dir);
        } catch (ReflectionException $e) {
            $this->controllerClasses = [];
        }
    }


    /**
     * @param string $scanDir
     * @return ControllerClass[]
     * @throws ReflectionException
     */
    private function generateControllerClass(string $scanDir): array
    {
        $arrayControllerClass = [];
        $files = scandir(getcwd() . DIRECTORY_SEPARATOR . $scanDir.DIRECTORY_SEPARATOR);
        if (is_array($files)) {
            foreach ($files as $file) {
                if (str_contains($file, '.php')) {
                    require_once($scanDir.DIRECTORY_SEPARATOR.$file);
                    $arrayClass = get_declared_classes();
                    $className = end($arrayClass);
                    if (!$className) {
                        continue;
                    }
                    $reflectionClass = new ReflectionClass($className);
                    $arrayControllerClass[] = new ControllerClass($reflectionClass);
                }
            }
        }
        return $arrayControllerClass;
    }

    /**
     * @param App $app
     * @return void
     */
    public function generateRoutes(App $app): void
    {
        foreach ($this->controllerClasses as $controllerClass) {
            $controllerClass->generateRouting($app);
        }
    }

}
