<?php

namespace RouterAnnotations\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @Target("CLASS")
 * @NamedArgumentConstructor
 */
class Controller
{
    /**
     * @var string $path
     */
    public string $path = '';

    public function __construct(?string $path)
    {
        $this->path = '';
        if ($path !== null) {
            $this->path = $path;
        }
    }
}
