<?php
/**
 * Item controller.
 */

namespace App\Controller;

//use App\Entity\Item;
use App\Repository\ItemRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ItemController.
 */
#[Route('/items')]
class ItemController extends AbstractController
{
    /**
     * Index action.
     *
     * @param ItemRepository $repository Item repository
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'item_index',
        methods: 'GET'
    )]
    public function index(Request $request, ItemRepository $taskRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $taskRepository->queryAll(),
            $request->query->getInt('page', 1),
            TaskRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render('item/index.html.twig', ['pagination' => $pagination]);
    }

    
    /**
     * Show action.
     *
     * @param ItemRepository $repository Item repository
     * @param int              $id         Item id
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'item_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(ItemRepository $repository, int $id): Response
    {
        $item = $repository->findOneById($id);

        return $this->render(
            'item/show.html.twig',
            ['item' => $item]
        );
    }
}
