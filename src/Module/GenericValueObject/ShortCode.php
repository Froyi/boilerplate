<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function strlen;

/**
 * Class ShortCode
 * @package Project\Module\GenericValueObject
 */
class ShortCode extends DefaultGenericValueObject
{
    protected const SHORTCODE_LENGTH = 4;

    protected const POSSIBLE_CHARS = 'ABCDEFGHJKLMNPRSTUVWXYZ23456789';

    protected const POSSIBLE_LETTERS = 'ABCDEFGHJKLMNPRSTUVWXYZ';

    /** @var string $shortCode */
    protected $shortCode;

    /**
     * ShortCode constructor.
     *
     * @param string $shortCode
     */
    protected function __construct(string $shortCode)
    {
        $this->shortCode = $shortCode;
    }

    /**
     * @param string $shortCode
     *
     * @return ShortCode
     */
    public static function fromString(string $shortCode): self
    {
        self::ensureShortCodeIsValid($shortCode);

        return new self(self::convertShortCode($shortCode));
    }

    /**
     * @return ShortCode
     * @throws InvalidArgumentException
     */
    public static function generateShortCode(): self
    {
        $shortCode = '';
        $arrayShuffledLetter = self::getShuffledLetterArray();
        $arrayShuffled = self::getShuffledArray();

        for ($i = 0; $i < self::SHORTCODE_LENGTH; $i++) {
            if (empty($shortCode) === true) {
                $shortCode .= $arrayShuffledLetter[array_rand($arrayShuffledLetter)];
                continue;
            }

            $shortCode .= $arrayShuffled[array_rand($arrayShuffled)];
        }

        return self::fromString($shortCode);
    }

    /**
     * @param string $shortCode
     *
     */
    protected static function ensureShortCodeIsValid(string $shortCode): void
    {
        if (strlen($shortCode) !== self::SHORTCODE_LENGTH) {
            throw new InvalidArgumentException('This shortcode is too long: ' . $shortCode);
        }

        if (is_numeric($shortCode[0]) === true) {
            throw new InvalidArgumentException('This shortcode has a numeric value at first: ' . $shortCode);
        }
    }

    /**
     * @param string $shortCode
     *
     * @return string
     */
    protected static function convertShortCode(string $shortCode): string
    {
        return strtoupper($shortCode);
    }

    /**
     * @return array
     */
    protected static function getShuffledArray(): array
    {
        $array = str_split(self::POSSIBLE_CHARS);
        shuffle($array);

        return $array;
    }

    /**
     * @return array
     */
    protected static function getShuffledLetterArray(): array
    {
        $array = str_split(self::POSSIBLE_LETTERS);
        shuffle($array);

        return $array;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->shortCode;
    }

    /**
     * @return string
     */
    public function getShortCode(): string
    {
        return $this->shortCode;
    }
}

