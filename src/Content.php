<?php
declare(strict_types=1);

namespace Project;

/**
 * Class Content
 * @package Project
 */
class Content
{
    /** @var string CONTENT_PATH */
    protected const CONTENT_PATH = ROOT_PATH . '/content';

    /** @var string DELIMETER */
    protected const DELIMETER = '##';

    /** @var array $content */
    protected $content = [];

    /** @var self $instance */
    public static $instance;

    /**
     * @return Content
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Configuration constructor.
     */
    public function __construct()
    {
        $this->content = [];

        $this->setConfigByDir(self::CONTENT_PATH);
    }

    /**
     * @param string $name
     * @param array|null $parameter
     *
     * @return string
     */
    public static function getContent(string $name, ?array $parameter = []): string
    {
        $content = new self();

        if (empty($name) === true) {
            return '';
        }

        $contentValue = $content->getEntryByName($name, $parameter);

        if ($contentValue === null) {
            return '';
        }

        return $contentValue;
    }

    /**
     * @param string $name
     *
     * @param array $parameter
     *
     * @return null|string
     */
    public function getEntryByName(string $name, array $parameter = []): ?string
    {
        if (!isset($this->content[$name])) {
            return null;
        }

        $content = $this->content[$name];
        if (empty($parameter) === true) {
            return $content;
        }

        return $this->replacePlaceholder($content, $parameter);
    }

    /**
     * @param string $dir
     */
    protected function setConfigByDir(string $dir): void
    {
        $list = scandir($dir, 1);

        foreach ($list as $entry) {
            if ($entry === '..' || $entry === '.') {
                continue;
            }

            $entryPath = $dir . '/' . $entry;

            if (is_file($entryPath) === true) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                /** @noinspection PhpIncludeInspection */
                $this->content = array_merge($this->content, include $entryPath);
            }

            if (is_dir($entryPath) === true) {
                $this->setConfigByDir($entryPath);
            }
        }
    }

    /**
     * @param string $content
     * @param array $parameter
     *
     * @return null|string
     */
    protected function replacePlaceholder(string $content, array $parameter): ?string
    {
        foreach ($parameter as $key => $value) {
            $searchString = self::DELIMETER . $key . self::DELIMETER;

            $content = str_replace($searchString, $value, $content);
        }

        if (strpos($content, self::DELIMETER) !== false) {
            return null;
        }

        return $content;
    }
}