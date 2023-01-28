<?php

namespace RouterAnnotations\Models;

use JsonSerializable;
use RouterAnnotations\Utils\ControllerClass;
use RouterAnnotations\Utils\MethodClass;
use RouterAnnotations\Utils\RouterCache;

class ControllerModel implements JsonSerializable
{
    /**
     * @var class-string $classStr
     */
    private string $classStr;

    /**
     * @var string $fileName
     */
    private string $fileName;

    /**
     * @var string $path
     */
    private string $path;

    private string $regexPath;

    /**
     * @var MethodModel[]
     */
    private array $methods;

    /**
     * @param ControllerClass|null $controllerClass
     */
    public function __construct(?ControllerClass $controllerClass = null)
    {
        if ($controllerClass !== null) {
            $this->classStr = $controllerClass->getClassStr();
            $this->fileName = $controllerClass->getFileName();
            $this->path = $controllerClass->getController()->path;
            $this->regexPath = RouterCache::generateRegexPath($this->path);
            $this->methods = $this->generateMethodsModel($controllerClass->getMethods());
        }
    }

    /**
     * @return class-string
     */
    public function getClassStr(): string
    {
        return $this->classStr;
    }

    /**
     * @param class-string $classStr
     */
    public function setClassStr(string $classStr): void
    {
        $this->classStr = $classStr;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
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
     * @param string $path
     */
    public function setRegexPath(string $path): void
    {
        $this->regexPath = $path;
    }

    /**
     * @return MethodModel[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param MethodModel[] $methods
     */
    public function setMethods(array $methods): void
    {
        $this->methods = $methods;
    }

    /**
     * @param MethodClass[] $methodsClass
     * @return MethodModel[]
     */
    private function generateMethodsModel(array $methodsClass): array
    {
        $methods = [];
        foreach ($methodsClass as $methodClass) {
            $methods[] = new MethodModel($this->path, $methodClass);
        }
        return $methods;
    }

    /**
     * @param array<string, string[]|string|string[][]> $object
     * @return ControllerModel
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function buildOne(array $object): ControllerModel
    {
        $model = new ControllerModel();
        $model->setClassStr(strval($object['class']));
        $model->setFileName(strval($object['fileName']));
        $model->setPath(strval($object['basePath']));
        $model->setRegexPath(strval($object['regexBasePath']));
        $methods = $object['methods'];
        $arrayMethods = [];
        if (is_array($methods)) {
            foreach ($methods as $method) {
                if (is_array($method)) {
                    $arrayMethods[] = MethodModel::buildOne($method);
                }
            }
        }
        $model->setMethods($arrayMethods);
        return $model;
    }

    /**
     * @return array<string, MethodModel[]|string>
     */
    public function jsonSerialize(): array
    {
        return [
            'class' => $this->classStr,
            'fileName' => $this->fileName,
            'basePath' => $this->path,
            'regexBasePath' => $this->regexPath,
            'methods' => $this->getMethods()
        ];
    }
}
