<?php

namespace RouterAnnotations\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Controller
{
    /**
     * @var string $path
     */
    public string $path;

    /**
     * @param string $path
     */
    public function __construct(string $path = '')
    {
        $this->path = $path;
    }
}
