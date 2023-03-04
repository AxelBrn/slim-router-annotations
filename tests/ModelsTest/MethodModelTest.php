<?php

namespace Tests\ModelsTest;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use ReflectionException;
use RouterAnnotations\Models\MethodModel;
use Tests\CustomTestCase;

class MethodModelTest extends CustomTestCase
{
    /**
     * @param array<string, mixed> $params
     * @return void
     */
    #[DataProvider('buildOneProvider')]
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
