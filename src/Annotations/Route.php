<?php

namespace RouterAnnotations\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
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
}
