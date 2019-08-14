<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function strlen;

/**
 * Class StartNumberText
 * @package Project\Module\GenericValueObject
 */
class StartNumberText extends DefaultGenericValueObject
{
    protected const MIN_TEXT_LENGTH = 1;

    protected const MAX_TEXT_LENGTH = 15;

    /** @var string $startNumberText */
    protected $startNumberText;

    /**
     * StartNumberText constructor.
     * @param string $startNumberText
     */
    protected function __construct(string $startNumberText)
    {
        $this->startNumberText = $startNumberText;
    }

    /**
     * @param string $startNumberText
     * @return StartNumberText
     */
    public static function fromString(string $startNumberText): self
    {
        self::ensureStartNumberTextIsValid($startNumberText);
        $startNumberText = self::convertStartNumberText($startNumberText);

        return new self($startNumberText);
    }

    /**
     * @param string $startNumberText
     */
    protected static function ensureStartNumberTextIsValid(string $startNumberText): void
    {
        if (strlen($startNumberText) < self::MIN_TEXT_LENGTH) {
            throw new InvalidArgumentException('The text is not long enough.', 1);
        }

        if (strlen($startNumberText) > self::MAX_TEXT_LENGTH) {
            throw new InvalidArgumentException('The text too long.', 1);
        }
    }

    /**
     * @param string $startNumberText
     * @return string
     */
    protected static function convertStartNumberText(string $startNumberText): string
    {
        return trim($startNumberText);
    }

    /**
     * @return string
     */
    public function getStartNumberText(): string
    {
        return $this->startNumberText;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->startNumberText;
    }
}

