<?php
/**
 * Category controller.
 */

namespace App\Controller;

use App\Service\CategoryServiceInterface;
use App\Form\Type\CategoryType;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController.
 */
#[Route('/categories')]
class CategoryController extends AbstractController
{
    /**
     * Translator.
     */
    private TranslatorInterface $translator;
    /**
     * Category service.
     */
    private CategoryServiceInterface $categoryService;

    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService Category service
     * @param TranslatorInterface      $translator      Translator
     */
    public function __construct(CategoryServiceInterface $categoryService, TranslatorInterface $translator)
    {
        $this->categoryService = $categoryService;
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
        name: 'category_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->categoryService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('category/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'category_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        if ($this->getUser() && in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
            $category = new Category();
            $form = $this->createForm(CategoryType::class, $category);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->categoryService->save($category);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.created_successfully')
                );

                return $this->redirectToRoute('category_index');
            }

            return $this->render(
                'category/create.html.twig',
                ['form' => $form->createView()]
            );
        }
        $this->addFlash(
            'warning',
            $this->translator->trans('message.page_not_found')
        );

        return $this->redirectToRoute('category_index');
    }

    /**
     * Edit action.
     *
     * @param Request  $request  HTTP request
     * @param Category $category Category entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'category_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Category $category): Response
    {
        if ($this->getUser() && in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
            $form = $this->createForm(CategoryType::class, $category, [
                'method' => 'PUT',
                'action' => $this->generateUrl('category_edit', ['id' => $category->getId()]),
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->categoryService->save($category);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.created_successfully')
                );

                return $this->redirectToRoute('category_index');
            }

            return $this->render(
                'category/edit.html.twig',
                [
                'form' => $form->createView(),
                'category' => $category,
                ]
            );
        }
        $this->addFlash(
            'warning',
            $this->translator->trans('message.page_not_found')
        );

        return $this->redirectToRoute('category_index');
    }

    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param Category $category Category entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'category_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Category $category): Response
    {
        if ($this->getUser() && in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
            if (!$this->categoryService->canBeDeleted($category)) {
                $this->addFlash(
                    'warning',
                    $this->translator->trans('message.category_contains_items')
                );

                return $this->redirectToRoute('category_index');
            }
            $form = $this->createForm(CategoryType::class, $category, [
                'method' => 'DELETE',
                'action' => $this->generateUrl('category_delete', ['id' => $category->getId()]),
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->categoryService->delete($category);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.deleted_successfully')
                );

                return $this->redirectToRoute('category_index');
            }

            return $this->render('category/delete.html.twig', [
                'form' => $form->createView(),
                'category' => $category,
            ]);
        }
        $this->addFlash(
            'warning',
            $this->translator->trans('message.page_not_found')
        );

        return $this->redirectToRoute('category_index');
    }

    /**
     * Show action.
     *
     * @param Category $category Category entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'category_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Category $category): Response
    {
        return $this->render(
            'category/show.html.twig',
            ['category' => $category]
        );
    }
}