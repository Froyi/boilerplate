<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

/**
 * Class PasswordHash
 * @package Project\Module\GenericValueObject
 */
class PasswordHash
{
    const CRYPTER = PASSWORD_BCRYPT;

    /** @var string $passwordHash */
    protected $passwordHash;

    /**
     * PasswordHash constructor.
     * @param string $passwordHash
     */
    protected function __construct(string $passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    /**
     * @param Password $clearPassword
     * @return PasswordHash
     */
    public static function fromPassword(Password $clearPassword): self
    {
        return new self(self::generatePasswordHash($clearPassword));
    }

    /**
     * @param string $passwordHash
     * @return PasswordHash
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $passwordHash): self
    {
        self::ensurePasswordHashIsValid($passwordHash);

        return new self($passwordHash);
    }

    /**
     * @param Password $clearPassword
     * @return string
     */
    protected static function generatePasswordHash(Password $clearPassword): string
    {
        return password_hash($clearPassword->getPassword(), self::CRYPTER);
    }

    /**
     * @param string $passwordHash
     * @throws \InvalidArgumentException
     */
    protected static function ensurePasswordHashIsValid(string $passwordHash): void
    {
        if (strlen($passwordHash) !== 60) {
            throw new \InvalidArgumentException('Dieser PasswordHash ist nicht valide!', 1);
        }
    }

    /**
     * @param Password $toVerifyPassword
     * @return bool
     */
    public function verifyPassword(Password $toVerifyPassword): bool
    {
        return password_verify($toVerifyPassword->getPassword(), $this->passwordHash);
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->passwordHash;
    }
}

