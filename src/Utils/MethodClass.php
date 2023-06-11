<?php

namespace RouterAnnotations\Utils;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionAttribute;
use ReflectionMethod;
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

        $closure = fn (Request $request, Response $response, array $args) => (new DependencyInjection($request, $response, $container, $args))
            ->invokeMethod($this->getMethod(), $controller);

        $route = $app->map($this->getRoute()->methods, $path, function (Request $request, Response $response, array $args) use ($closure) {
            try {
                $result = $closure($request, $response, $args);
            } catch (Exception $e) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ]) ?: $e->getMessage());
                $result = $response->withHeader('Content-Type', 'application/json')->withStatus(500);
            }
            
            return $result;
        });
        $middlewareClass->addMiddlewares($route);
    }
}
