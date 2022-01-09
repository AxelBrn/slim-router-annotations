<?php

namespace RouterAnnotations\Models;

use JsonSerializable;
use RouterAnnotations\Utils\MethodClass;

class MethodModel implements JsonSerializable
{
    /**
     * @var string $name
     */
    private string $name;

    /**
     * @var string $path
     */
    private string $path;

    /**
     * @var string $regexPath
     */
    private string $regexPath;

    /**
     * @var string[] $httpMethods
     */
    private array $httpMethods;

    /**
     * @param string $basePath
     * @param MethodClass|null $methodClass
     */
    public function __construct(string $basePath = '', ?MethodClass $methodClass = null)
    {
        if ($methodClass !== null) {
            $this->name = $methodClass->getMethod()->getName();
            $this->path = $basePath . $methodClass->getRoute()->path;
            $this->regexPath = $this->generateRegexPath();
            $this->httpMethods = $methodClass->getRoute()->methods;
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getRegexPath(): string
    {
        return $this->regexPath;
    }

    /**
     * @param string $regexPath
     */
    public function setRegexPath(string $regexPath): void
    {
        $this->regexPath = $regexPath;
    }

    /**
     * @return string[]
     */
    public function getHttpMethods(): array
    {
        return $this->httpMethods;
    }

    /**
     * @param string[] $httpMethods
     */
    public function setHttpMethods(array $httpMethods): void
    {
        $this->httpMethods = $httpMethods;
    }

    /**
     * @return string
     */
    private function generateRegexPath(): string
    {
        $patterns = [
            '/{[a-zA-Z0-9]+:([^}]+)}/',
            '/{[a-zA-Z0-9]+}/'
        ];
        $replacements = [
            '$1',
            '[a-zA-Z0-9-_]+'
        ];
        $regexGen = preg_replace($patterns, $replacements, $this->path);
        return $regexGen !== null ? $regexGen : $this->path;
    }

    /**
     * @param array<string, string[]|string> $object
     * @return MethodModel
     */
    public static function buildOne(array $object): MethodModel
    {
        $model = new MethodModel();
        $model->setName(strval($object['name']));
        $model->setPath(strval($object['path']));
        $model->setRegexPath(strval($object['regexPath']));
        $httpMethods = $object['httpMethods'];
        if (is_array($httpMethods)) {
            $model->setHttpMethods($httpMethods);
        }

        return $model;
    }

    /**
     * @return array<string, string[]|string>
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'path' => $this->path,
            'regexPath' => $this->regexPath,
            'httpMethods' => $this->httpMethods
        ];
    }
}
