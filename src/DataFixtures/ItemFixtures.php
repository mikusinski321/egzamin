<?php
/**
 * Item fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Item;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class ItemFixtures.
 */
class ItemFixtures extends Fixture
{
    /**
     * Faker.
     *
     * @var Generator
     */
    protected Generator $faker;

    /**
     * Persistence object manager.
     *
     * @var ObjectManager
     */
    protected ObjectManager $manager;

    /**
     * Load.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();

        for ($i = 0; $i < 15; ++$i) {
            $item = new Item();
            $item->setItemId($i);
            $item->setCategoryId(rand(0,5));
            $item->setTitle($this->faker->name);
            $item->setPublisher($this->faker->name);
            $item->setAuthor($this->faker->name);
            $item->setCreateTime(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $item->setQuantity(rand(0,500));
            $manager->persist($item);
        }

        $manager->flush();
    }
}
