<?php
/**
 * Item service interface.
 */

namespace App\Service;

use App\Entity\Item;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface ItemServiceInterface.
 */
interface ItemServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int   $page    Page number
     * @param array $filters Filters
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface;

    /**
     * Prepare filters for the tasks list.
     *
     * @param array<string, int> $filters Raw filters from request
     *
     * @return array<string, object> Result array of filters
     */
    public function prepareFilters(array $filters): array;

    /**
     * Save entity.
     *
     * @param Item $item Item entity
     */
    public function save(Item $item): void;

    /**
     * Delete entity.
     *
     * @param Item $item Item entity
     */
    public function delete(Item $item): void;

    /**
     * Find by id.
     *
     * @param int $id Item id
     *
     * @return Item|null Item entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Item;

    /**
     * Can Item be deleted?
     *
     * @param Item $item Category entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Item $item): bool;
}
