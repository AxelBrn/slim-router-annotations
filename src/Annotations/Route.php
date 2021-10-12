<?php

namespace RouterAnnotations\Annotations;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Route {

    /**
     * @var string $path
     */
    public string $path;

    /**
     * @var string $method
     */
    public string $method;

}