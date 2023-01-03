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

    public function __construct(string $dir = '')
    {
        $this->controllerClasses = [];
        if ($dir !== '') {
            try {
                $this->controllerClasses = $this->generateControllerClass($dir);
            } catch (ReflectionException $e) {
                $this->controllerClasses = [];
            }
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

    /**
     * @return ControllerModel[]
     */
    public function getControllersModel(): array
    {
        $controllersModel = [];
        foreach ($this->controllerClasses as $controllerClass) {
            $controllersModel[] = new ControllerModel($controllerClass);
        }
        return $controllersModel;
    }

    /**
     * @param ControllerModel[] $controllersModel
     * @return ControllerModel[]
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function filterRoutes(array $controllersModel): array
    {
        $requestURI = $_SERVER['REQUEST_URI'];
        return array_filter($controllersModel, function (ControllerModel $item) use ($requestURI) {
            return str_starts_with($requestURI, $item->getPath());
        });
    }

    /**
     * @param ControllerModel $controllerModel
     * @return void
     * @throws ReflectionException
     */
    private function generateControllerClassProd(ControllerModel $controllerModel): void
    {
        $arrayController = $this->controllerClasses;
        require_once $controllerModel->getFileName();
        $reflectionClass = new ReflectionClass($controllerModel->getClassStr());
        $arrayController[] = new ControllerClass($reflectionClass);
        $this->controllerClasses = $arrayController;
    }

    /**
     * @param App $app
     * @return void
     */
    public function readCache(App $app): void
    {
        $cache = new RouterCache();
        $controllersModel = $cache->retrieveCache();
        $controllersModel = $this->filterRoutes($controllersModel);
        foreach ($controllersModel as $controllerModel) {
            try {
                $this->generateControllerClassProd($controllerModel);
            } catch (ReflectionException $e) {
                continue;
            }
        }
        $this->generateRoutes($app);
    }
}
