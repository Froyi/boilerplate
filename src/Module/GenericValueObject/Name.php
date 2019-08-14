<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function strlen;

/**
 * Class Name
 * @package Project\Module\GenericValueObject
 */
class Name extends DefaultGenericValueObject
{
    /** @var array SEARCH_NAME_ARRAY */
    protected const SEARCH_NAME_ARRAY = ['Med.', 'Phil.', 'Habil.', 'Em.', 'Von ', 'Van ', 'Der ', 'Zu '];

    /** @var array REPLACE_NAME_ARRAY */
    protected const REPLACE_NAME_ARRAY = ['med.', 'phil.', 'habil.', 'em.', 'von ', 'van ', 'der ', 'zu '];

    /** @var string $name */
    protected $name;

    /**
     * Name constructor.
     * @param string $name
     */
    protected function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @return Name
     */
    public static function fromString(string $name): self
    {
        $name = self::convertName($name);
        self::ensureNameIsValid($name);

        return new self($name);
    }

    /**
     * @param string $name
     */
    protected static function ensureNameIsValid(string $name): void
    {
        if (strlen($name) < 2) {
            throw new InvalidArgumentException('Dieser name ist zu kurz!', 1);
        }

        if (preg_match('/\d/', $name) === 1) {
            throw new InvalidArgumentException('Dieser name ist nicht gültig!', 1);
        }

        $names = explode(' ', $name);
        foreach ($names as $nameParts) {
            if (strlen($nameParts) < 2) {
                throw new InvalidArgumentException('Dieser Name ist nicht gültig!', 1);
            }
        }
    }

    /**
     * @param string $name
     * @return string
     */
    protected static function convertName(string $name): string
    {
        if (strpos($name, '-') >= 0) {
            $names = explode('-', $name);

            foreach ($names as $key => $lastname) {
                $lastname = ucwords(trim($lastname));
                $names[$key] = $lastname;
            }

            $name = implode('-', $names);
        } else {
            $name = ucwords(trim($name));
        }

        return str_replace(self::SEARCH_NAME_ARRAY, self::REPLACE_NAME_ARRAY, $name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @param Name $name
     *
     * @return bool
     */
    public function eval(Name $name): bool
    {
        return ($this->name === $name->getName());
    }
}

