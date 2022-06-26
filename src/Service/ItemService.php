<?php
/**
 * Item service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\ItemRepository;
use App\Repository\OrderRepository;
use App\Entity\Item;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ItemService.
 */
class ItemService implements ItemServiceInterface
{
    /**
     * Category service.
     */
    private CategoryServiceInterface $categoryService;

    /**
     * Item repository.
     */
    private ItemRepository $itemRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Item repository.
     */
    private OrderRepository $orderRepository;

    /**
     * Constructor.
     *
     * @param ItemRepository           $itemRepository  Item repository
     * @param PaginatorInterface       $paginator       Paginator
     * @param CategoryServiceInterface $categoryService Category Service
     * @param OrderRepository          $orderRepository Order repository
     */
    public function __construct(
        ItemRepository $itemRepository,
        PaginatorInterface $paginator,
        CategoryServiceInterface $categoryService,
        OrderRepository $orderRepository
    ) {
        $this->categoryService = $categoryService;
        $this->itemRepository = $itemRepository;
        $this->paginator = $paginator;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Get paginated list.
     *
     * @param int                $page    Page number
     * @param array<string, int> $filters Filters array
     *
     * @return PaginationInterface Paginated list
     *
     * @throws NonUniqueResultException
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->itemRepository->queryAll($filters),
            $page,
            ItemRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Prepare filters for the tasks list.
     *
     * @param array<string, int> $filters Raw filters from request
     *
     * @return Category[] Result array of filters
     *
     * @psalm-return array{category?: Category}
     *
     * @throws NonUniqueResultException
     */
    public function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (!empty($filters['category_id'])) {
            $category = $this->categoryService->findOneById($filters['category_id']);
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        return $resultFilters;
    }

    /**
     * Save entity.
     *
     * @param Item $item Item entity
     */
    public function save(Item $item): void
    {
        $this->itemRepository->save($item);
    }

    /**
     * Delete entity.
     *
     * @param Item $item Item entity
     */
    public function delete(Item $item): void
    {
        $this->itemRepository->delete($item);
    }

    /**
     * Edit entity.
     *
     * @param Item $item Item entity
     */
    public function edit(Item $item): void
    {
        $this->itemRepository->save($item);
    }

    /**
     * Find by id.
     *
     * @param int $id Item id
     *
     * @return Item|null Item entity
     */
    public function findOneById(int $id): ?Item
    {
        return $this->itemRepository->findOneBy(['id' => $id]);
    }

    /**
     * Can Category be deleted?
     *
     * @param Item $item Item entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Item $item): bool
    {
        try {
            $result = $this->orderRepository->countByItem($item);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }
}
