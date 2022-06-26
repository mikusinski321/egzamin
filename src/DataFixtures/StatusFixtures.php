<?php
/**
 * Status fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Status;

/**
 * Class StatusFixtures.
 */
class StatusFixtures extends AbstractBaseFixtures
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
        $this->createMany(4, 'statuses', function (int $i) {
            $names = ['Ordered', 'Accepted', 'Denied', 'Returned'];
            $status = new Status();
            $status->setId($i);
            $status->setName($names[$i]);

            return $status;
        });

        $this->manager->flush();
    }
}
