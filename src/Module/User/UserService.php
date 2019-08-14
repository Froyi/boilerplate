<?php
declare(strict_types=1);

namespace Project\Module\User;

use Project\Module\Database\Database;
use Project\Module\DefaultService;
use Project\Module\GenericValueObject\Email;
use Project\Module\GenericValueObject\Id;
use Project\Module\GenericValueObject\Password;

class UserService extends DefaultService
{
    /** @var  UserFactory $newsFactory */
    protected $userFactory;

    /** @var  UserRepository $userRepository */
    protected $userRepository;

    /**
     * UserService constructor.
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        parent::__construct();

        $this->userFactory = new UserFactory();
        $this->userRepository = new UserRepository($database);
    }

    /**
     * @param Email    $email
     * @param Password $password
     *
     * @return null|User
     */
    public function getLoggedInUserByEmailAndPassword(Email $email, Password $password): ?User
    {
        $userResult = $this->userRepository->getUserByEmail($email);

        if (empty($userResult)) {
            return null;
        }

        return $this->userFactory->getLoggedInUserByPassword($userResult, $password);
    }


    /**
     * @param Id $userId
     *
     * @return null|User
     */
    public function getLoggedInUserByUserId(Id $userId): ?User
    {
        $userResult = $this->userRepository->getUserByUserId($userId);

        if (empty($userResult) === true) {
            return null;
        }

        return $this->userFactory->getLoggedInUserByUserId($userResult);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function logoutUser(User $user): bool
    {
        return $user->logout();
    }

    /**
     * @param Id $userId
     *
     * @return null|User
     */
    public function getUserByUserId(Id $userId): ?User
    {
        $userData = $this->userRepository->getUserByUserId($userId);

        return $this->getSingleUserByData($userData);
    }

    /**
     * @return array
     */
    public function getAllUser(): array
    {
        $allUserData = $this->userRepository->getAllUser();

        return $this->getUsersByData($allUserData);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function saveOrUpdateUser(User $user): bool
    {
        if ($this->getUserByUserId($user->getUserId()) === null) {
            return $this->userRepository->saveUser($user);
        }

        return $this->userRepository->updateUser($user);
    }

    /**
     * @param array $userData
     *
     * @return array
     */
    protected function getUsersByData(array $userData): array
    {
        $userArray = [];

        if (empty($userData) === true) {
            return null;
        }

        foreach ($userData as $singleUserData) {
            $user = $this->getSingleUserByData($singleUserData);

            if ($user !== null) {
                $userArray[$user->getUserId()->toString()] = $user;
            }
        }

        return $userArray;
    }

    /**
     * @param $userData
     *
     * @return null|User
     */
    protected function getSingleUserByData($userData): ?User
    {
        if (empty($userData) === true) {
            return null;
        }

        return $this->userFactory->getUser($userData);
    }
}