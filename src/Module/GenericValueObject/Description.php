<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function strlen;

/**
 * Class Description
 * @package Project\Module\GenericValueObject
 */
class Description extends DefaultGenericValueObject
{
    /** @var int MIN_LENGTH */
    protected const MIN_LENGTH = 2;

    /** @var string $description */
    protected $description;

    /**
     * Description constructor.
     * @param string $description
     */
    protected function __construct(string $description)
    {
        $this->description = $description;
    }

    /**
     * @param string $description
     * @return Description
     */
    public static function fromString(string $description): self
    {
        $description = self::convertDescription($description);
        self::ensureDescriptionIsValid($description);

        return new self($description);
    }

    /**
     * @param string $description
     */
    protected static function ensureDescriptionIsValid(string $description): void
    {
        if (strlen($description) < self::MIN_LENGTH) {
            throw new InvalidArgumentException('The description is not long enough.', 1);
        }
    }

    /**
     * @param string $description
     * @return string
     */
    protected static function convertDescription(string $description): string
    {
        return trim($description);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->description;
    }
}

