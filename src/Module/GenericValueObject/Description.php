<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

/**
 * Class Description
 * @package Project\Module\GenericValueObject
 */
class Description
{
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
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $description): self
    {
        self::ensureDescriptionIsValid($description);
        $description = self::convertDescription($description);

        return new self($description);
    }

    /**
     * @param string $description
     * @throws \InvalidArgumentException
     */
    protected static function ensureDescriptionIsValid(string $description): void
    {
        if (\strlen($description) < 2) {
            throw new \InvalidArgumentException('The description is not long enough.', 1);
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

