<?php

namespace RouterAnnotations\Utils;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionMethod;
use RouterAnnotations\Annotations\Route;

class MethodClass
{

    /**
     * @var Route $route
     */
    private Route $route;

    /**
     * @var ReflectionMethod $method
     */
    private ReflectionMethod $method;

    public function __construct(ReflectionMethod $method)
    {
        $this->method = $method;
        $reader = new AnnotationReader();
        $routeAnnot = $reader->getMethodAnnotation($method, Route::class);
        if ($routeAnnot) {
            $this->route = $routeAnnot;
        }
    }

    /**
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }


    /**
     * @return ReflectionMethod
     */
    public function getMethod(): ReflectionMethod
    {
        return $this->method;
    }

}
