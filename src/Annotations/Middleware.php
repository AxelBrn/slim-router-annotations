<?php

namespace RouterAnnotations\Annotations;

use Closure;
use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use Psr\Http\Server\MiddlewareInterface;

/**
 * @Annotation
 * @Target("METHOD")
 * @NamedArgumentConstructor
 */
class Middleware
{
    /**
     * @var class-string<MiddlewareInterface> $classString
     */
    public string $classString;

    /**
     * @param class-string<MiddlewareInterface> $classString
     */
    public function __construct(string $classString)
    {
        $this->classString = $classString;
    }
}
