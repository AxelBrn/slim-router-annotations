<?php

namespace Tests\ModelsTest;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use RouterAnnotations\Models\ControllerModel;
use RouterAnnotations\Utils\ControllerClass;
use Tests\CustomTestCase;
use Tests\Example\HelloController;

class ControllerModelTest extends CustomTestCase
{

    public function testConstruct(): void
    {
        $reflectionClass = new ReflectionClass(HelloController::class);
        $controllerModel = new ControllerModel(new ControllerClass($reflectionClass));
        $this->assertInstanceOf(ControllerModel::class, $controllerModel);
        $this->assertSame('Tests\Example\HelloController', $controllerModel->getClassStr());
        $this->assertSame('/api/example/hello', $controllerModel->getPath());
        $this->assertSame('/api/example/hello', $controllerModel->getRegexPath());
        $this->assertEquals(json_encode([[
            'name' => 'index',
            'path' => '/api/example/hello/{name}',
            'regexPath' => '/api/example/hello/[a-zA-Z0-9-_]+',
            'httpMethods' => ['GET']
        ]]), json_encode($controllerModel->getMethods()));
    }

    /**
     * @param array<string, string[]> $object
     * @return void
     */
    #[DataProvider('buildOneProvider')]
    public function testBuildOne(array $object): void
    {
        $model = ControllerModel::buildOne($object);
        $this->assertInstanceOf(ControllerModel::class, $model);
    }

    /**
     * @return array<string, string[]>
     */
    public static function buildOneProvider(): array
    {
        return static::generateJsonProvider('controller/build_one');
    }
}
