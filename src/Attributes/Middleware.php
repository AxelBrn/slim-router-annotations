<?php

namespace RouterAnnotations\Attributes;

use Closure;
use Attribute;
use Psr\Http\Server\MiddlewareInterface;


#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
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
