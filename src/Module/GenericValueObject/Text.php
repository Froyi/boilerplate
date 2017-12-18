<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

/**
 * Class Text
 * @package Project\Module\GenericValueObject
 */
class Text
{
    const MIN_TEXT_LENGTH = 50;

    /** @var string $text */
    protected $text;

    /**
     * Text constructor.
     * @param string $text
     */
    protected function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @param string $text
     * @return Text
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $text): self
    {
        self::ensureTextIsValid($text);
        $text = self::convertText($text);

        return new self($text);
    }

    /**
     * @param string $text
     * @throws \InvalidArgumentException
     */
    protected static function ensureTextIsValid(string $text): void
    {
        if (strlen($text) < self::MIN_TEXT_LENGTH) {
            throw new \InvalidArgumentException('The text is not long enough.', 1);
        }
    }

    /**
     * @param string $text
     * @return string
     */
    protected static function convertText(string $text): string
    {
        return trim($text);
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->text;
    }
}

