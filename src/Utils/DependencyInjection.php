<?php

namespace RouterAnnotations\Utils;

use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;

class DependencyInjection
{

    private Request $request;
    private Response $response;
    private ?ContainerInterface $container;

    /**
     * @var string[] $args
     */
    private array $args;

    /**
     * @param Request $request
     * @param Response $response
     * @param ContainerInterface|null $container
     * @param string[] $args
     */
    public function __construct(Request $request, Response $response, ?ContainerInterface $container, array $args) {
        $this->request = $request;
        $this->response = $response;
        $this->container = $container;
        $this->args = $args;
    }

    public function invokeMethod(ReflectionMethod $method, ?object $object): ?object
    {
        try {
            $parameters = $this->getArrayParameters($method->getParameters());
            $result = $method->isConstructor()
                ? $method->getDeclaringClass()->newInstanceArgs($parameters)
                : $method->invokeArgs($object, $parameters);
        } catch (ReflectionException $e) {
            $result = null;
        }
        return $result;
    }

    /**
     * @param class-string $typeName
     * @return null|object
     */
    private function getClassParameter(string $typeName): ?object
    {
        $result = null;
        $reflectionClass = new ReflectionClass($typeName);
        if ($reflectionClass->isSubclassOf(Request::class)) {
            $result = $this->request;
        } elseif ($reflectionClass->isSubclassOf(Response::class)) {
            $result = $this->response;
        } elseif ($this->container !== null && $this->container->has($typeName)) {
            $result = $this->container->get($typeName);
        } else if($result === null) {
            $result = $reflectionClass->getConstructor() !== null
                ? $this->invokeMethod($reflectionClass->getConstructor(), null)
                : $result = $reflectionClass->newInstance();
        }
        return $result;
    }

    /**
     * @param ReflectionNamedType $type
     * @param string $parameterName
     * @return string[]|string|null|Response|Request|object
     */
    private function getParameter(ReflectionNamedType $type, string $parameterName): array|string|null|object {
        $result = null;
        $typeName = $type->getName();
        $result = $this->args[$parameterName] ?? null;
        if ($typeName === 'array') {
            $result = $this->args;
        } elseif (class_exists($typeName)) {
            $result = $this->getClassParameter($typeName);
        } elseif (interface_exists($typeName) && $typeName === Request::class) {
            $result = $this->request;
        } elseif (interface_exists($typeName) && $typeName === Response::class) {
            $result = $this->response;
        }

        return $result;
    }

    /**
     * @param ReflectionParameter[] $reflectionParameters
     * @return array<int|string[]|Response|Request|object|string|null>
     */
    private function getArrayParameters(array $reflectionParameters): array
    {
        $parameters = [];
        foreach ($reflectionParameters as $parameter) {
            if ($parameter instanceof ReflectionParameter && $parameter->getType() instanceof ReflectionNamedType) {
                try {
                    $parameters[] = $this->getParameter($parameter->getType(), $parameter->getName());
                } catch (Exception $e) {
                    $parameters[] = null;
                }
            }
        }
        return $parameters;
    }

}
