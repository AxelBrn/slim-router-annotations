<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class CustomTestCase extends TestCase
{
    /**
     * @param string $filename
     * @return array<string,mixed>
     */
    public function generateJsonProvider(string $filename): array
    {
        $reflection = new ReflectionClass($this);
        $directory = dirname($reflection->getFileName() ?: __DIR__) ;
        $contents = file_get_contents($directory . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $filename . '.json');
        if (empty($contents)) {
            $contents = '';
        }
        return json_decode(
            $contents,
            true
        );
    }
}
