<?php

namespace RouterAnnotations\Utils;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RouterAnnotations\Annotations\Middleware;
use Slim\Interfaces\RouteInterface;

class MiddlewareClass
{
    /**
     * @var Middleware[] $middlewares
     */
    private array $middlewares;

    public function __construct(ReflectionMethod $method)
    {
        $reader = new AnnotationReader();
        $this->middlewares = $this->getMiddlewares($reader->getMethodAnnotations($method));
    }

    /**
     * @param object[] $annotations
     * @return Middleware[]
     */
    private function getMiddlewares(array $annotations): array
    {
        $result = [];
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Middleware) {
                $result[] = $annotation;
            }
        }
        return $result;
    }

    /**
     * @param RouteInterface $route
     * @return void
     */
    public function addMiddlewares(RouteInterface $route): void
    {
        foreach ($this->middlewares as $middleware) {
            try {
                $middlewareReflection = new ReflectionClass($middleware->classString);
                $route->add($middlewareReflection->newInstance());
            } catch (ReflectionException $e) {
                continue;
            }
        }
    }
}
