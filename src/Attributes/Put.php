<?php

namespace RouterAnnotations\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Put extends Route
{
    /**
     * @param string $path
     */
    public function __construct(string $path = '')
    {
        parent::__construct($path, ['PUT']);
    }
}
