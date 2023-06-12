<?php

namespace Tests\UtilsTest;

use RouterAnnotations\Utils\RouterCache;
use Tests\CustomTestCase;

class RouterCacheTest extends CustomTestCase
{
    private RouterCache $routerCache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routerCache = new RouterCache(true);
    }

    public function testConstruct(): void
    {
        $this->assertTrue(file_exists('./cache'));
    }

    /**
     * @dataProvider generateRegexPathProvider
     * @param string $path
     * @param string $expected
     * @return void
     */
    public function testGenerateRegexPath(string $path, string $expected): void
    {
        $this->assertSame($expected, RouterCache::generateRegexPath($path));
    }

    /**
     * @return array<string, mixed>
     */
    public static function generateRegexPathProvider(): array
    {
        return static::generateJsonProvider('RouterCache/generate_regex_path');
    }
}
