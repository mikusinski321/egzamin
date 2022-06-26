<?php

/**
 * User service interface.
 */

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save(User $user): void;

    /**
     * Find by id.
     *
     * @param int $id User id
     *
     * @return User|null User entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?User;
}
