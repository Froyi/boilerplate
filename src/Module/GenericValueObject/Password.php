<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

/**
 * Class Password
 * @package Project\Module\GenericValueObject
 */
class Password
{
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
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $password): self
    {
        self::ensurePasswordIsValid($password);

        return new self($password);
    }

    /**
     * @param string $password
     * @throws \InvalidArgumentException
     */
    protected static function ensurePasswordIsValid(string $password): void
    {
        if (strlen($password) < 5) {
            throw new \InvalidArgumentException('Dieser password ist zu kurz!', 1);
        }

        $uppercase = preg_match('@[A-Z]@', $password);
        if ($uppercase === false) {
            throw new \InvalidArgumentException('Dieses Passwort hat keine Großbuchstaben!', 1);
        }

        $lowercase = preg_match('@[a-z]@', $password);
        if ($lowercase === false) {
            throw new \InvalidArgumentException('Dieses Passwort enthält keinen Kleinbuchstaben!', 1);
        }

        $number = preg_match('@\d@', $password);
        if ($number === false) {
            throw new \InvalidArgumentException('Dieses Passwort enthält keine Zahl!', 1);
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

