<?php
/**
 * Status entity.
 */

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Status.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    /**
     * Primary key.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Name.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 45)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 45)]
    private ?string $name = null;

    /**
     * Setter for id.
     *
     * @param int|null $id Id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Getter for id.
     *
     * @return int|null $id Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for name.
     *
     * @param string|null $name Name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
