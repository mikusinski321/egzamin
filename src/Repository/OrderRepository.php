<?php
/**
 * Order repository.
 */

namespace App\Repository;

use App\Entity\Item;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 6;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial order.{id}',
                'partial item.{id, title, quantity}',
                'partial status.{id, name}'
            )
                ->join('order.item', 'item')
                ->join('order.status', 'status')
            ->orderBy('order.orderedAt', 'DESC');
    }

    /**
     * Save entity.
     *
     * @param Order $order Order entity
     */
    public function save(Order $order): void
    {
        $this->_em->persist($order);
        $this->_em->flush();
    }

    /**
     * Count items by order.
     *
     * @param Item $item Item
     *
     * @return int Number of orders with item
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByItem(Item $item): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('a.id'))
            ->from(Order::class, 'a')
            ->where('a.item = :item')
            ->setParameter('item', $item)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('order');
    }
}
