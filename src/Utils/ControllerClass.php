<?php

namespace RouterAnnotations\Utils;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RouterAnnotations\Attributes\Controller;
use Slim\App;

class ControllerClass
{
    /**
     * @var Controller $controller
     */
    private Controller $controller;

    /**
     * @var ReflectionClass<object> $reflectionClass
     */
    private ReflectionClass $reflectionClass;

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
        $this->reflectionClass = $class;
        $this->controller = new Controller();
        try {
            $this->controllerObject = $class->newInstance();
        } catch (ReflectionException $e) {
            $this->controllerObject = null;
        }
        $controllerAttributes = $class->getAttributes(Controller::class);
        $controllerAnnot = !empty($controllerAttributes) ? $controllerAttributes[0] : null;
        if ($controllerAnnot !== null && $controllerAnnot->getName() === Controller::class) {
            $this->controller = $controllerAnnot->newInstance();
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
     * @return class-string
     */
    public function getClassStr(): string
    {
        return $this->reflectionClass->getName();
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->reflectionClass->getFileName() ?: '';
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
