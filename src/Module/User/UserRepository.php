<?php
declare(strict_types = 1);

namespace Project\Module\User;

use Project\Module\DefaultRepository;
use Project\Module\GenericValueObject\Email;
use Project\Module\GenericValueObject\Id;

class UserRepository extends DefaultRepository
{
    protected const TABLE = 'user';

    protected const ORDERBY = 'userId';

    protected const ORDERKIND = 'ASC';

    /**
     * @param Email $email
     *
     * @return mixed
     */
    public function getUserByEmail(Email $email)
    {
        $query = $this->database->getNewSelectQuery(self::TABLE);

        $query->where('email', '=', $email->getEmail());

        return $this->database->fetch($query);
    }

    /**
     * @param Id $userId
     *
     * @return mixed
     */
    public function getUserByUserId(Id $userId)
    {
        $query = $this->database->getNewSelectQuery(self::TABLE);

        $query->where('userId', '=', $userId->toString());

        return $this->database->fetch($query);
    }

    /**
     * @return array
     */
    public function getAllUser(): array
    {
        $query = $this->database->getNewSelectQuery(self::TABLE);

        return $this->database->fetchAll($query);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function saveUser(User $user): bool
    {
        $query = $this->database->getNewInsertQuery(self::TABLE);

        $query->insert('userId', $user->getUserId()->toString());
        $query->insert('email', $user->getEmail()->getEmail());
        $query->insert('passwordHash', $user->getPasswordHash()->getPassword());

        return $this->database->execute($query);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function updateUser(User $user): bool
    {
        $query = $this->database->getNewUpdateQuery(self::TABLE);

        $query->where('userId', '=', $user->getUserId()->toString());

        $query->set('email', $user->getEmail()->getEmail());
        $query->set('passwordHash', $user->getPasswordHash()->getPassword());

        return $this->database->execute($query);
    }
}