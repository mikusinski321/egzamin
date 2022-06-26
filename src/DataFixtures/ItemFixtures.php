<?php
/**
 * Item fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Item;
use App\Entity\Category;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ItemFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class ItemFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'items', function () {
            $item = new Item();
            $item->setTitle($this->faker->unique()->word);
            $item->setPublisher($this->faker->name);
            $item->setAuthor($this->faker->name);
            $item->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $item->setQuantity(rand(0, 500));
            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $item->setCategory($category);

            return $item;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}
