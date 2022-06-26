<?php
/**
 * Order fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\Status;
use App\Entity\Item;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class OrderFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class OrderFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
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

        $this->createMany(10, 'orders', function () {
            $order = new Order();

            $order->setComment($this->faker->word);

            $order->setOrderedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $order->setNick($this->faker->name);
            $order->setEmail($this->faker->email);
            /** @var Item item */
            $item = $this->getRandomReference('items');
            $order->setItem($item);
            /** @var Status status */
            $status = $this->getRandomReference('statuses');
            $order->setStatus($status);

            return $order;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array {0: ItemFixtures::class}
     */
    public function getDependencies(): array
    {
        return [ItemFixtures::class];
    }
}
