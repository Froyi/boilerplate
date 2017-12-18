<?php
declare (strict_types=1);

namespace Project\View\ValueObject;

/**
 * Class TemplateDir
 * @package Project\View\ValueObject
 */
class TemplateDir
{
    /** @var string $templateDir */
    protected $templateDir;

    /**
     * TemplateDir constructor.
     * @param string $templateDir
     */
    protected function __construct(string $templateDir)
    {
        $this->templateDir = $templateDir;
    }

    /**
     * @param string $templateDir
     * @return TemplateDir
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $templateDir): self
    {
        self::ensureValueIsValid($templateDir);
        $templateDir = self::convertValue($templateDir);

        self::ensureTemplateDirExists($templateDir);

        return new self($templateDir);
    }

    /**
     * @param string $templateDir
     * @throws \InvalidArgumentException
     */
    protected static function ensureValueIsValid(string $templateDir): void
    {
        if (strlen($templateDir) < 3) {
            throw new \InvalidArgumentException('this template dir is too short ... minimum three chars');
        }
    }

    /**
     * @param string $templateDir
     * @return string
     */
    protected static function convertValue(string $templateDir): string
    {
        $tempDir = rtrim($templateDir, '/') . '/';

        return ROOT_PATH . '/templates' . $tempDir;
    }

    /**
     * @param string $templateDir
     * @throws \InvalidArgumentException
     */
    protected static function ensureTemplateDirExists(string $templateDir): void
    {
        if (is_dir($templateDir) === false) {
            throw new \InvalidArgumentException('this template dir is no valid dir ... it does not exists.');
        }
    }

    /**
     * @return string
     */
    public function getTemplateDir(): string
    {
        return $this->templateDir;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->templateDir;
    }
}