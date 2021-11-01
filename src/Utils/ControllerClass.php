<?php

namespace RouterAnnotations\Utils;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionMethod;
use RouterAnnotations\Annotations\Controller;

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
     * @param ReflectionClass<object> $class
     */
    public function __construct(ReflectionClass $class)
    {
        $reader = new AnnotationReader();
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
}
