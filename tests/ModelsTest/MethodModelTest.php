<?php

namespace Tests\ModelsTest;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RouterAnnotations\Models\MethodModel;
use RouterAnnotations\Utils\MethodClass;
use Tests\CustomTestCase;
use Tests\Example\Controllers\HelloController;

class MethodModelTest extends CustomTestCase
{

    public function testConstruct(): void
    {
        $reflectionClass = new ReflectionClass(HelloController::class);
        $reflectionMethod = $reflectionClass->getMethod('index');
        $methodModel = new MethodModel('', new MethodClass($reflectionMethod));
        $this->assertInstanceOf(MethodModel::class, $methodModel);
        $this->assertSame('index', $methodModel->getName());
        $this->assertSame('/{name}', $methodModel->getPath());
        $this->assertSame('/[a-zA-Z0-9-_]+', $methodModel->getRegexPath());
        $this->assertSame(['GET'], $methodModel->getHttpMethods());
    }

    /**
     * @dataProvider buildOneProvider
     * @param array<string, mixed> $params
     * @return void
     */
    public function testBuildOne(array $params): void
    {
        $methodModel = MethodModel::buildOne($params);
        $this->assertInstanceOf(MethodModel::class, $methodModel);
        $this->assertSame($params['name'], $methodModel->getName());
        $this->assertSame($params['path'], $methodModel->getPath());
        $this->assertSame($params['regexPath'], $methodModel->getRegexPath());
        $this->assertSame($params['httpMethods'], $methodModel->getHttpMethods());
    }

    /**
     * @return array<string, mixed>
     */
    public static function buildOneProvider(): array
    {
        return static::generateJsonProvider('method/build_one');
    }

}
