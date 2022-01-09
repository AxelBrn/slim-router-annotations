<?php

namespace RouterAnnotations\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @Target("METHOD")
 * @NamedArgumentConstructor
 */
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
