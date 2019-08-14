<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;

/**
 * Class Housenumber
 * @package Project\Module\GenericValueObject
 */
class Housenumber extends DefaultGenericValueObject
{
    /** @var string $housenumber */
    protected $housenumber;

    /**
     * Housenumber constructor.
     *
     * @param string $housenumber
     */
    protected function __construct(string $housenumber)
    {
        $this->housenumber = $housenumber;
    }

    /**
     * @param string $housenumber
     *
     * @return Housenumber
     * @throws InvalidArgumentException
     */
    public static function fromString($housenumber): self
    {
        self::ensureHousenumberIsValid($housenumber);

        return new self((string)$housenumber);
    }

    /**
     * @param string $housenumber
     *
     * @throws InvalidArgumentException
     */
    protected static function ensureHousenumberIsValid($housenumber): void
    {
        if (empty($housenumber) === true) {
            throw new InvalidArgumentException('The housenumber is empty!', 1);
        }
    }

    /**
     * @return string
     */
    public function getHousenumber(): string
    {
        return $this->housenumber;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->housenumber;
    }
}

