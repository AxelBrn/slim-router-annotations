<?php

namespace Tests\Example\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use RouterAnnotations\Attributes\Controller;
use RouterAnnotations\Attributes\Get;
use RouterAnnotations\Attributes\Middleware;
use Tests\Example\Middlewares\ExampleAfterMiddleware;
use Tests\Example\Middlewares\ExampleBeforeMiddleware;
use Tests\Example\Middlewares\ExampleBeforeMiddlewareBis;

#[Controller('/api/example/middlewares')]
class MiddlewareController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param string $name
     * @return Response
     */
    #[Get('/no-middleware')]
    public function noMiddleware(Request $request, Response $response, string $name): Response {
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param string $name
     * @return Response
     */
    #[Get('/before-middleware')]
    #[Middleware(ExampleBeforeMiddleware::class)]
    public function beforeMidleware(Request $request, Response $response, string $name): Response {
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param string $name
     * @return Response
     */
    #[Get('/after-middleware')]
    #[Middleware(ExampleAfterMiddleware::class)]
    public function afterMiddleware(Request $request, Response $response, string $name): Response {
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param string $name
     * @return Response
     */
    #[Get('/multiple-middleware')]
    #[Middleware(ExampleBeforeMiddleware::class)]
    #[Middleware(ExampleAfterMiddleware::class)]
    public function multipleMiddleware(Request $request, Response $response, string $name): Response {
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param string $name
     * @return Response
     */
    #[Get('/before-bis-first-middleware')]
    #[Middleware(ExampleBeforeMiddlewareBis::class)]
    #[Middleware(ExampleBeforeMiddleware::class)]
    #[Middleware(ExampleAfterMiddleware::class)]
    public function beforeBisFirst(Request $request, Response $response, string $name): Response {
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param string $name
     * @return Response
     */
    #[Get('/before-bis-second-middleware')]
    #[Middleware(ExampleBeforeMiddleware::class)]
    #[Middleware(ExampleAfterMiddleware::class)]
    #[Middleware(ExampleBeforeMiddlewareBis::class)]
    public function beforeBisSecond(Request $request, Response $response, string $name): Response {
        return $response;
    }

}