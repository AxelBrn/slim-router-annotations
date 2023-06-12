<?php

namespace Tests\UtilsTest;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use RouterAnnotations\Utils\MiddlewareClass;
use Slim\Factory\AppFactory;
use Tests\Example\Controllers\MiddlewareController;
use Tests\CustomTestCase;

class MiddlewareClassTest extends CustomTestCase
{

    /**
     * @dataProvider constructProvider
     * 
     * @param string $methodName
     * @param int $nbMiddleware
     */
    public function testConstruct(string $methodName, int $nbMiddleware): void
    {
        $refectionClass = new ReflectionClass(MiddlewareController::class);
        $reflectionMethod = $refectionClass->getMethod($methodName);
        $middlewareClass = new MiddlewareClass($reflectionMethod);
        $reflectedClass = new ReflectionClass($middlewareClass);
        $middlewares = $reflectedClass->getProperty('middlewares');
        $this->assertSame($nbMiddleware, count($middlewares->getValue($middlewareClass)));
    }

    /**
     * @dataProvider addMiddlewaresProvider
     * 
     * @param string $methodName
     * @param int $nbMiddleware
     */
    public function testAddMiddlewares(string $methodName, string $expectedResponse): void
    {
        $refectionClass = new ReflectionClass(MiddlewareController::class);
        $reflectionMethod = $refectionClass->getMethod($methodName);
        $middlewareClass = new MiddlewareClass($reflectionMethod);

        $slimApp = AppFactory::create();
        
        $routeExample = $slimApp->get('/test', function (ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
            return $response;
        });
        $middlewareClass->addMiddlewares($routeExample);
        
        $mockRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $response = $routeExample->run($mockRequest);

        $this->assertSame($expectedResponse, (string) $response->getBody());
    }

    /**
     * @return array<string, mixed>
     */
    public static function constructProvider(): array
    {
        return static::generateJsonProvider('MiddlewareClass/construct');
    }

    /**
     * @return array<string, mixed>
     */
    public static function addMiddlewaresProvider(): array
    {
        return static::generateJsonProvider('MiddlewareClass/add_middlewares');
    }

}
