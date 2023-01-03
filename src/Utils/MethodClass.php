<?php

namespace RouterAnnotations\Utils;

use Closure;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionAttribute;
use ReflectionMethod;
use ReflectionParameter;
use RouterAnnotations\Attributes\Controller;
use RouterAnnotations\Attributes\Route;
use Slim\App;

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
        $routeAttributes = $this->method->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);
        $routeAnnot = !empty($routeAttributes) ? $routeAttributes[0]->newInstance() : null;
        if ($routeAnnot instanceof Route) {
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

    /**
     * @param ReflectionParameter[] $reflectionParameters
     * @param Request $request
     * @param Response $response
     * @param ContainerInterface|null $container
     * @param string[] $args
     * @return array<int|string[]|Response|Request|string|null>
     */
    private function getArrayParameters(array $reflectionParameters, Request $request, Response $response, ?ContainerInterface $container, array $args): array
    {
        $parameters = [];
        foreach ($reflectionParameters as $parameter) {
            if ($parameter instanceof ReflectionParameter) {
                $result = $args[$parameter->getName()] ?? null;
                if ($parameter->getClass() !== null) {
                    if ($parameter->getClass()->getName() === Request::class) {
                        $result = $request;
                    } elseif ($parameter->getClass()->getName() === Response::class) {
                        $result = $response;
                    } elseif ($container !== null) {
                        $result = $container->get($parameter->getClass()->getName());
                    }
                } elseif ($parameter->isArray()) {
                    $result = $args;
                }
                $parameters[] = $result;
            }
        }
        return $parameters;
    }

    /**
     * @param Controller $controllerAnnotation
     * @param App $app
     * @param object $controller
     * @return void
     */
    public function generateRoute(Controller $controllerAnnotation, App $app, object $controller): void
    {
        $path = $controllerAnnotation->path . $this->getRoute()->path;
        $container = $app->getContainer();
        $middlewareClass = new MiddlewareClass($this->method);

        $closure = fn (Request $request, Response $response, array $args) => $this
                ->getMethod()
                ->invokeArgs(
                    $controller,
                    $this->getArrayParameters(
                        $this->getMethod()->getParameters(),
                        $request,
                        $response,
                        $container,
                        $args
                    )
                );

        $route = $app->map($this->getRoute()->methods, $path, function (Request $request, Response $response, array $args) use ($closure) {
            return $closure($request, $response, $args);
        });
        $middlewareClass->addMiddlewares($route);
    }
}
