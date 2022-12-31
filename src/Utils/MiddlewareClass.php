<?php

namespace RouterAnnotations\Utils;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RouterAnnotations\Attributes\Middleware;
use Slim\Interfaces\RouteInterface;

class MiddlewareClass
{
    /**
     * @var Middleware[] $middlewares
     */
    private array $middlewares;

    public function __construct(ReflectionMethod $method)
    {
        $middlewareAttributes = $method->getAttributes(Middleware::class);
        $this->middlewares = $this->getMiddlewares($middlewareAttributes ?? []);
    }

    /**
     * @param ReflectionAttribute<Middleware>[] $attributes
     * @return Middleware[]
     */
    private function getMiddlewares(array $attributes): array
    {
        $result = [];
        foreach ($attributes as $attribute) {
            $newInstance = $attribute->newInstance();
            if ($newInstance instanceof Middleware) {
                $result[] = $newInstance;
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
