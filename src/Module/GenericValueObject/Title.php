<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

/**
 * Class Title
 * @package Project\Module\GenericValueObject
 */
class Title
{
    const TITLE_MIN_LENGTH = 5;

    /** @var string $title */
    protected $title;

    /**
     * Title constructor.
     * @param string $title
     */
    protected function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param string $title
     * @return Title
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $title): self
    {
        self::ensureTitleIsValid($title);
        $title = self::convertTitle($title);

        return new self($title);
    }

    /**
     * @param string $title
     * @throws \InvalidArgumentException
     */
    protected static function ensureTitleIsValid(string $title): void
    {
        if (strlen($title) < self::TITLE_MIN_LENGTH) {
            throw new \InvalidArgumentException('The title is too short', 1);
        }
    }

    /**
     * @param string $title
     * @return string
     */
    protected static function convertTitle(string $title): string
    {
        return ucfirst(trim($title));
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->title;
    }
}

