<?php declare(strict_types=1);

namespace Project\Module\User;

use Project\Module\DefaultModel;
use Project\Module\GenericValueObject\Email;
use Project\Module\GenericValueObject\Id;
use Project\Module\GenericValueObject\Password;
use Project\Module\GenericValueObject\PasswordHash;

/**
 * Class User
 * @package     Project\Module\User
 */
class User extends DefaultModel
{
    /** @var Id $userId */
    protected $userId;

    /** @var Email $email */
    protected $email;

    /** @var PasswordHash $passwordHash */
    protected $passwordHash;

    /** @var  bool $isLoggedIn */
    protected $isLoggedIn;

    /**
     * User constructor.
     *
     * @param Id           $userId
     * @param Email        $email
     * @param PasswordHash $passwordHash
     */
    public function __construct(Id $userId, Email $email, PasswordHash $passwordHash)
    {
        parent::__construct();

        $this->userId = $userId;
        $this->email = $email;
        $this->passwordHash = $passwordHash;

        $this->isLoggedIn = false;
    }

    /**
     * @return Id
     */
    public function getUserId(): Id
    {
        return $this->userId;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return PasswordHash
     */
    public function getPasswordHash(): PasswordHash
    {
        return $this->passwordHash;
    }

    /**
     * @param Password $password
     *
     * @return bool
     */
    public function loginUser(Password $password): bool
    {
        if ($this->passwordHash->verifyPassword($password) === true) {
            $this->loginSuccessUser();

            return true;
        }

        $this->logoutUser();

        return false;
    }

    /**
     * @return bool
     */
    public function loginUserBySession(): bool
    {
        if (isset($_SESSION['userId']) && $_SESSION['userId'] === $this->userId->toString()) {
            $this->loginSuccessUser();

            return true;
        }

        $this->logoutUser();

        return false;
    }

    /**
     * @return bool
     */
    public function logout(): bool
    {
        return $this->logoutUser();
    }

    protected function loginSuccessUser(): void
    {
        $this->isLoggedIn = true;
        $_SESSION['userId'] = $this->userId->toString();
    }

    /**
     * @return bool
     */
    protected function logoutUser(): bool
    {
        $this->isLoggedIn = false;

        if ($_SESSION !== null) {
            unset($_SESSION['userId']);
        }

        return true;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'userId' => $this->getUserId()->toString(),
            'email' => $this->getEmail()->getEmail(),
            'passwordHash' => $this->getPasswordHash()->getPassword()
        ];
    }
}