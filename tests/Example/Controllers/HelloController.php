<?php

namespace Tests\Example\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use RouterAnnotations\Attributes\Controller;
use RouterAnnotations\Attributes\Route;

#[Controller('/api/example/hello')]
class HelloController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param string $name
     * @return Response
     */
    #[Route('/{name}')]
    public function index(Request $request, Response $response, string $name): Response {
        $response->getBody()->write("Hello ".$name." !");
        return $response;
    }

}