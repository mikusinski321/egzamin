<?php
/**
 * Order service.
 */

namespace App\Service;

use App\Repository\OrderRepository;
use App\Repository\StatusRepository;
use App\Repository\ItemRepository;
use App\Entity\Order;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class OrderService.
 */
class OrderService implements OrderServiceInterface
{
    /**
     * Item repository.
     */
    private ItemRepository $itemRepository;
    /**
     * Status repository.
     */
    private StatusRepository $statusRepository;
    /**
     * Order repository.
     */
    private OrderRepository $orderRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param ItemRepository     $itemRepository   Item repository
     * @param StatusRepository   $statusRepository Status repository
     * @param OrderRepository    $orderRepository  Order repository
     * @param PaginatorInterface $paginator        Paginator
     */
    public function __construct(ItemRepository $itemRepository, StatusRepository $statusRepository, OrderRepository $orderRepository, PaginatorInterface $paginator)
    {
        $this->itemRepository = $itemRepository;
        $this->statusRepository = $statusRepository;
        $this->orderRepository = $orderRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->orderRepository->queryAll(),
            $page,
            OrderRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Order entity.
     *
     * @param Order $order Order entity
     */
    public function order(Order $order): void
    {
        $status = $this->statusRepository->findOneBy(['id' => 0]);
        $order->setStatus($status);
        $this->orderRepository->save($order);
    }

    /**
     * Accept entity.
     *
     * @param Order $order Order entity
     */
    public function accept(Order $order): void
    {
        $status = $this->statusRepository->findOneBy(['id' => 1]);
        $order->setStatus($status);
        $item = $this->itemRepository->findOneBy(['id' => ($order->getItem())->getId()]);
        $item->setQuantity(($item->getQuantity() - 1));
        $this->itemRepository->save($item);
        $this->orderRepository->save($order);
    }

    /**
     * Deny entity.
     *
     * @param Order $order Order entity
     */
    public function deny(Order $order): void
    {
        $status = $this->statusRepository->findOneBy(['id' => 2]);
        $order->setStatus($status);
        $this->orderRepository->save($order);
    }

    /**
     * Return entity.
     *
     * @param Order $order Order entity
     */
    public function return(Order $order): void
    {
        $status = $this->statusRepository->findOneBy(['id' => 3]);
        $order->setStatus($status);
        $item = $this->itemRepository->findOneBy(['id' => ($order->getItem())->getId()]);
        $item->setQuantity(($item->getQuantity() + 1));
        $this->itemRepository->save($item);
        $this->orderRepository->save($order);
    }

    /**
     * Find by id.
     *
     * @param int $id Order id
     *
     * @return Order|null Order entity
     */
    public function findOneById(int $id): ?Order
    {
        return $this->orderRepository->findOneBy(['id' => $id]);
    }
}
