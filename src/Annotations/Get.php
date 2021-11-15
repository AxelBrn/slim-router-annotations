<?php

namespace RouterAnnotations\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @Target("METHOD")
 * @NamedArgumentConstructor
 */
class Get extends Route
{
    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct($path);
    }
}
