<?php
/**
 * Order entity.
 */

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Order.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'orders')]
class Order
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
     * Comment.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 45)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 45)]
    private ?string $comment = null;

    /**
     * Created at.
     *
     * @var DateTimeImmutable|null
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?DateTimeImmutable $orderedAt;

    /**
     * Email.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 45, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 45)]
    #[Assert\Email]
    private ?string $email;

    /**
     * Nick.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 45)]
    private ?string $nick;

    /**
     * Item.
     *
     * @var Item|null
     */
    #[ORM\ManyToOne(targetEntity: Item::class)]
    #[Assert\NotBlank]
    private ?Item $item = null;

    /**
     * Status.
     *
     * @var Status|null
     */
    #[ORM\ManyToOne(targetEntity: Status::class)]
    #[Assert\NotBlank]
    private ?Status $status = null;

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
     * Setter for comment.
     *
     * @param string|null $comment Comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * Getter for comment.
     *
     * @return string|null $comment Comment
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Setter for ordered at.
     *
     * @param DateTimeImmutable|null $orderedAt Ordered at
     */
    public function setOrderedAt(?DateTimeImmutable $orderedAt): void
    {
        $this->orderedAt = $orderedAt;
    }

    /**
     * Getter for ordered at.
     *
     * @return DateTimeImmutable|null $orderedAt Ordered at
     */
    public function getOrderedAt(): ?DateTimeImmutable
    {
        return $this->orderedAt;
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
     * Setter for nick.
     *
     * @param string|null $nick Nick
     */
    public function setNick(?string $nick): void
    {
        $this->nick = $nick;
    }

    /**
     * Getter for nick.
     *
     * @return string|null $nick Nick
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * Getter for item.
     *
     * @return Item|null item
     */
    public function getItem(): ?Item
    {
        return $this->item;
    }

    /**
     * Setter for item.
     *
     * @param Item|null $item Item
     */
    public function setItem(?Item $item): void
    {
        $this->item = $item;
    }

    /**
     * Setter for status.
     *
     * @param Status|null $status Status
     */
    public function setStatus(?Status $status): void
    {
        $this->status = $status;
    }

    /**
     * Getter for status.
     *
     * @return Status|null $status Status
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }
}
