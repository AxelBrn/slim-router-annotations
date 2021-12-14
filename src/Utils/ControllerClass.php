<?php

namespace RouterAnnotations\Utils;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RouterAnnotations\Annotations\Controller;
use Slim\App;

class ControllerClass
{
    /**
     * @var Controller $controller
     */
    private Controller $controller;

    /**
     * @var MethodClass[]
     */
    private array $methods;

    /**
     * @var object|null $controllerObject
     */
    private ?object $controllerObject;

    /**
     * @param ReflectionClass<object> $class
     */
    public function __construct(ReflectionClass $class)
    {
        $this->controller = new Controller();
        $reader = new AnnotationReader();
        try {
            $this->controllerObject = $class->newInstance();
        } catch (ReflectionException $e) {
            $this->controllerObject = null;
        }
        $controllerAnnot = $reader->getClassAnnotation($class, Controller::class);
        if ($controllerAnnot !== null) {
            $this->controller = $controllerAnnot;
        }
        $this->setMethods($class->getMethods());
    }

    /**
     * @param ReflectionMethod[] $methods
     * @return void
     */
    private function setMethods(array $methods): void
    {
        $array = [];
        foreach ($methods as $method) {
            if ($method->isPublic() && !$method->isConstructor()) {
                $array[] = new MethodClass($method);
            }
        }
        $this->methods = $array;
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @return MethodClass[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param App $app
     * @return void
     */
    public function generateRouting(App $app): void
    {
        if ($this->controllerObject !== null) {
            foreach ($this->getMethods() as $methodClass) {
                $methodClass->generateRoute($this->controller, $app, $this->controllerObject);
            }
        }
    }
}
