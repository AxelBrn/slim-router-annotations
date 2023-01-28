<?php

namespace RouterAnnotations\Utils;

use RouterAnnotations\Models\ControllerModel;

class RouterCache
{
    private const CACHE_DIRECTORY = 'cache';

    private const CACHE_FILE_NAME = 'router.cache.json';

    /**
     * @var string $basePath
     */
    private string $basePath;

    /**
     * @var string $fullpath
     */
    private string $fullPath;

    /**
     * @param bool $useCwd
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(bool $useCwd = false)
    {
        $cwd = getcwd();
        $this->basePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        if ($useCwd && $cwd !== false) {
            $this->basePath = $cwd . DIRECTORY_SEPARATOR;
        } elseif ($cwd !== false) {
            $this->basePath = $cwd . '/../';
        }
        $this->init();
    }

    private function init(): void
    {
        $dirPath = $this->basePath . self::CACHE_DIRECTORY;
        if (!is_dir($dirPath)) {
            mkdir($dirPath);
        }
        $this->fullPath = $dirPath. DIRECTORY_SEPARATOR . self::CACHE_FILE_NAME;
    }

    /**
     * @param ControllerModel[] $controllersModel
     * @return bool
     */
    public function storeCache(array $controllersModel): bool
    {
        return (bool) file_put_contents($this->fullPath, json_encode($controllersModel));
    }

    /**
     * @return ControllerModel[]
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function retrieveCache(): array
    {
        $result = [];
        $content = file_get_contents($this->fullPath);
        if ($content !== false) {
            $array = json_decode($content, true);
            foreach ($array as $item) {
                $result[] = ControllerModel::buildOne($item);
            }
        }
        return $result;
    }

    public static function generateRegexPath(string $path): string
    {
        $patterns = [
            '/{[a-zA-Z0-9]+:([^}]+)}/',
            '/{[a-zA-Z0-9]+}/',
            '/\[(.*)\]/'
        ];
        $replacements = [
            '$1',
            '[a-zA-Z0-9-_]+',
            '($1)?'
        ];
        $regexGen = preg_replace($patterns, $replacements, $path);
        return $regexGen !== null ? $regexGen : $path;
    }
}
