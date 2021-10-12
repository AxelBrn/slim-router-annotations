<?php

namespace RouterAnnotations\Annotations;
use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Controller {

    /**
     * @var string $path
     */
    public string $path;

}