<?php

namespace RouterAnnotations\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    /**
     * @var string $path
     * @Required
     */
    public string $path;

    /**
     * @var string[] $methods
     * @Required
     */
    public array $methods;

    /**
     * @param string $path
     * @param string[] $methods
     */
    public function __construct(string $path = '', array $methods = ['GET'])
    {
        $this->path = $path;
        $this->methods = $methods;
    }
}
