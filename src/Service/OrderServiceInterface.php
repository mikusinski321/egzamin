<?php
/**
 * Order service interface.
 */

namespace App\Service;

use App\Entity\Order;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface OrderServiceInterface.
 */
interface OrderServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Accept entity.
     *
     * @param Order $order Order entity
     */
    public function accept(Order $order): void;

    /**
     * Deny entity.
     *
     * @param Order $order Order entity
     */
    public function deny(Order $order): void;

    /**
     * Order entity.
     *
     * @param Order $order Order entity
     */
    public function order(Order $order): void;

    /**
     * Return entity.
     *
     * @param Order $order Order entity
     */
    public function return(Order $order): void;

    /**
     * Find by id.
     *
     * @param int $id Order id
     *
     * @return Order|null Order entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Order;
}
