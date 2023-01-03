# slim-router-annotations

![PHP CI branch develop](https://github.com/AxelBrn/slim-router-annotations/actions/workflows/ci.yml/badge.svg)

A simple router for Slim 4 applications. (the micro framework for PHP)  This package allows you to annotate the functions of your controllers to generate routes.

##  :zap: Installation

This section is not available actually

## :dart: Usages

Instantiate Annotations Reader :
```php
<?php
// public/index.php

require __DIR__ . '/../vendor/autoload.php';
use Slim\Factory\AppFactory;
use RouterAnnotations\SlimRouterAnnotations;

$app = AppFactory::create();

SlimRouterAnnotations::read($app, '../src/controllers');

$app->run();
```

### Basic controller example

```php
<?php
// src/controllers/HelloWorldController.php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use RouterAnnotations\Attributes\Controller;
use RouterAnnotations\Attributes\Get;

#[Controller('/api/v1/hello')]
class HelloWorldController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param string $name
     * @return Response
     */
    #[Get('/{name}')]
    public function index(Request $request, Response $response, string $name): Response {
        $response->getBody()->write("Hello ".$name." !");
        return $response;
    }
}
```

## :books: Documentation

comming soon... :rocket: