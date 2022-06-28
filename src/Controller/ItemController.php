<?php
/**
 * Item controller.
 */

namespace App\Controller;

use App\Service\ItemServiceInterface;
use App\Service\OrderServiceInterface;
use App\Form\Type\ItemType;
use App\Form\Type\OrderType;
use App\Entity\Item;
use App\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ItemController.
 */
#[Route('/items')]
class ItemController extends AbstractController
{
    /**
     * Item service.
     */
    private ItemServiceInterface $itemService;
    /**
     * Order service.
     */
    private OrderServiceInterface $orderService;
    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param ItemServiceInterface  $itemService  Item service
     * @param OrderServiceInterface $orderService Order service
     * @param TranslatorInterface   $translator   Translator
     */
    public function __construct(ItemServiceInterface $itemService, OrderServiceInterface $orderService, TranslatorInterface $translator)
    {
        $this->orderService = $orderService;
        $this->itemService = $itemService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'item_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $filters = $this->getFilters($request);
        $pagination = $this->itemService->getPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );

        return $this->render('item/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'item_create', methods: 'GET|POST')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item, ['action' => $this->generateUrl('item_create')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->itemService->save($item);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/create.html.twig', [
                'form' => $form->createView(),
        ]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Item    $item    Item entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'item_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Item $item): Response
    {
        $form = $this->createForm(ItemType::class, $item, [
            'method' => 'PUT',
            'action' => $this->generateUrl('item_edit', ['id' => $item->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->itemService->save($item);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/edit.html.twig', [
                'form' => $form->createView(),
                'item' => $item,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Item    $item    Item entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'item_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Item $item): Response
    {
        if (!$this->itemService->canBeDeleted($item)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.orders_contains_item')
            );

            return $this->redirectToRoute('item_index');
        }
        $form = $this->createForm(FormType::class, $item, [
                'method' => 'DELETE',
                'action' => $this->generateUrl('item_delete', ['id' => $item->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->itemService->delete($item);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/delete.html.twig', [
                'form' => $form->createView(),
                'item' => $item,
        ]);
    }

    /**
     * Order action.
     *
     * @param Request $request HTTP request
     * @param Item    $item    Item entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/order', name: 'item_order', requirements: ['id' => '[1-9]\d*'], methods: 'GET|POST')]
    public function order(Request $request, Item $item): Response
    {
        if (0 === $item->getQuantity()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.page_not_found')
            );

            return $this->redirectToRoute('item_index');
        }
        $order = new Order();
        $order->setItem($item);
        $form = $this->createForm(OrderType::class, $order, [
            'method' => 'POST',
            'action' => $this->generateUrl('item_order', ['id' => $item->getId()]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->orderService->order($order);

            $this->addFlash(
                'success',
                $this->translator->trans('message.ordered_successfully')
            );

            return $this->redirectToRoute('item_index');
        }

        return $this->render(
            'item/order.html.twig',
            ['form' => $form->createView(),
            'order' => $order,            ]
        );
    }

    /**
     * Show action.
     *
     * @param Item $item Item entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'item_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Item $item): Response
    {
        return $this->render(
            'item/show.html.twig',
            ['item' => $item]
        );
    }

    /**
     * Get filters from request.
     *
     * @param Request $request HTTP request
     *
     * @return array<string, int> Array of filters
     *
     * @psalm-return array{category_id: int}
     */
    private function getFilters(Request $request): array
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');

        return $filters;
    }
}
