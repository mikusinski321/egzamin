<?php
/**
 * User service.
 */

namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class OrderService.
 */
class UserService implements UserServiceInterface
{
    /**
     * User repository.
     */
    private UserRepository $userRepository;
    /**
     * Password hasher.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Constructor.
     *
     * @param UserRepository              $userRepository User repository
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     */
    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save(User $user): void
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            )
        );
        $this->userRepository->save($user);
    }

    /**
     * Find by id.
     *
     * @param int $id User id
     *
     * @return User|null User entity
     */
    public function findOneById(int $id): ?User
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }
}
