<?php

namespace Tests\ModelsTest;

use ReflectionClass;
use ReflectionException;
use RouterAnnotations\Models\MethodModel;
use Tests\CustomTestCase;

class MethodModelTest extends CustomTestCase
{
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
     * @dataProvider generateRegexPathProvider
     * @param string $path
     * @param string $expected
     * @return void
     * @throws ReflectionException
     */
    public function testGenerateRegexPath(string $path, string $expected): void
    {
        $model = new MethodModel();
        $model->setPath($path);
        $classReflection = new ReflectionClass(MethodModel::class);
        $methodReflection = $classReflection->getMethod('generateRegexPath');
        $methodReflection->setAccessible(true);
        $this->assertSame($expected, $methodReflection->invoke($model));
    }

    /**
     * @return array<string, mixed>
     */
    public function buildOneProvider(): array
    {
        return $this->generateJsonProvider('method/build_one');
    }

    /**
     * @return array<string, mixed>
     */
    public function generateRegexPathProvider(): array
    {
        return $this->generateJsonProvider('method/generate_regex_path');
    }
}
