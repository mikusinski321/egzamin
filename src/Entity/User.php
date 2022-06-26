<?php
/**
 * User entity.
 */

namespace App\Entity;

use App\Entity\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Primary key.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Email.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 45, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 45)]
    #[Assert\Email]
    private ?string $email = null;

    /**
     * Password.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 450)]
    #[Assert\NotBlank]
    private ?string $password = null;

    /**
     * Roles.
     *
     * @var array<int, string>
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Setter for email.
     *
     * @param string|null $email Email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * Getter for email.
     *
     * @return string|null $email Email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     *
     * @return string|null Username
     */
    public function getUsername(): ?string
    {
        return (string) $this->email;
    }

    /**
     * Getter for roles.
     *
     * @return array|null Roles
     *
     * @see UserInterface
     */
    public function getRoles(): ?array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = UserRole::ROLE_USER->value;

        return array_unique($roles);
    }

    /**
     * Setter for roles.
     *
     * @param array|null $roles Roles
     *
     * @return static
     */
    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Getter for password.
     *
     * @return string|null Password
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Setter for password.
     *
     * @param string|null $password Password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     *
     * @return void
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
