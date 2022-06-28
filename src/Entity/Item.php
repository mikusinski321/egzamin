<?php
/**
 * Item entity.
 */

namespace App\Entity;

use App\Repository\ItemRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Item.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
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
     * Title.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 45)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 45)]
    private ?string $title = null;

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
    private ?DateTimeImmutable $createdAt;

    /**
     * Quantity.
     *
     * @var int|null
     */
    #[ORM\Column(type: 'integer')]
    #[Assert\Type('int')]
    #[Assert\NotBlank]
    #[Assert\Range(min: 0)]
    private ?int $quantity = null;

    /**
     * Author.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 45)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 45)]
    private ?string $author = null;

    /**
     * Publisher.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 45)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 45)]
    private ?string $publisher = null;

    /**
     * Category.
     *
     * @var Category|null
     */
    #[ORM\ManyToOne(
        targetEntity: Category::class,
        fetch: 'EXTRA_LAZY'
    )]
    private ?Category $category = null;

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
     * Setter for created at.
     *
     * @param DateTimeImmutable|null $createdAt Created at
     */
    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for created at.
     *
     * @return DateTimeImmutable|null $createdAt Created at
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Getter for Quantity.
     *
     * @return int|null quantity
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * Setter for quantity.
     *
     * @param int|null $quantity Quantity
     */
    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * Setter for title.
     *
     * @param string|null $title Title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for Quantity.
     *
     * @return string|null title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for author.
     *
     * @param string|null $author Author
     */
    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    /**
     * Getter for author.
     *
     * @return string|null $author Author
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Setter for publisher.
     *
     * @param string|null $publisher Publisher
     */
    public function setPublisher(?string $publisher): void
    {
        $this->publisher = $publisher;
    }

    /**
     * Getter for publisher.
     *
     * @return string|null $publisher Publisher
     */
    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    /**
     * Setter for category.
     *
     * @param Category|null $category Category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * Getter for category.
     *
     * @return Category|null $category Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }
}
