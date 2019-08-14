<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function strlen;

/**
 * Class Password
 * @package Project\Module\GenericValueObject
 */
class Password extends DefaultGenericValueObject
{
    /** @var int GENERATED_LENGTH */
    protected const GENERATED_LENGTH = 8;

    protected const POSSIBLE_CHARS = 'abcdefghjklmnprstuvwxyzABCDEFGHJKLMNPRSTUVWXYZ23456789.,#+*-?!';

    /** @var string $password */
    protected $password;

    /**
     * Password constructor.
     * @param string $password
     */
    protected function __construct(string $password)
    {
        $this->password = $password;
    }

    /**
     * @param string $password
     * @return Password
     */
    public static function fromString(string $password): self
    {
        self::ensurePasswordIsValid($password);

        return new self($password);
    }

    /**
     * @param int|null $length
     *
     * @return Password
     */
    public static function generatePassword(int $length = null): self
    {
        $password = '';

        if ($length === null) {
            $length = self::GENERATED_LENGTH;
        }

        $array = str_split(self::POSSIBLE_CHARS);
        shuffle($array);

        for ($i = 0; $i < $length; $i++) {
            $password .= $array[array_rand($array)];
        }

        return self::fromString($password);
    }

    /**
     * @param string $password
     */
    protected static function ensurePasswordIsValid(string $password): void
    {
        if (strlen($password) < 5) {
            throw new InvalidArgumentException('Dieser password ist zu kurz!', 1);
        }

        $uppercase = preg_match('@[A-Z]@', $password);
        if ($uppercase === false) {
            throw new InvalidArgumentException('Dieses Passwort hat keine Großbuchstaben!', 1);
        }

        $lowercase = preg_match('@[a-z]@', $password);
        if ($lowercase === false) {
            throw new InvalidArgumentException('Dieses Passwort enthält keinen Kleinbuchstaben!', 1);
        }

        $number = preg_match('@\d@', $password);
        if ($number === false) {
            throw new InvalidArgumentException('Dieses Passwort enthält keine Zahl!', 1);
        }
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->password;
    }
}

