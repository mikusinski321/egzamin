<?php
/**
 * Order controller.
 */

namespace App\Controller;

use App\Service\OrderServiceInterface;
use App\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController.
 */
#[Route('/orders')]
#[IsGranted('ROLE_ADMIN')]
class OrderController extends AbstractController
{
    /**
     * Translator.
     */
    private TranslatorInterface $translator;
    /**
     * Order service.
     */
    private OrderServiceInterface $orderService;

    /**
     * Constructor.
     *
     * @param OrderServiceInterface $orderService Order service
     * @param TranslatorInterface   $translator   Translator
     */
    public function __construct(OrderServiceInterface $orderService, TranslatorInterface $translator)
    {
        $this->orderService = $orderService;
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
        name: 'order_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->orderService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('order/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Accept action.
     *
     * @param Request $request HTTP request
     * @param Order   $order   Order entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/accept', name: 'order_accept', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function accept(Request $request, Order $order): Response
    {
        $form = $this->createForm(FormType::class, $order, [
                'method' => 'PUT',
                'action' => $this->generateUrl('order_accept', ['id' => $order->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->orderService->accept($order);

            $this->addFlash(
                'success',
                $this->translator->trans('message.accepted_successfully')
            );

            return $this->redirectToRoute('order_index');
        }

        return $this->render(
            'order/confirm.html.twig',
            [
                'form' => $form->createView(),
                'order' => $order,
                ]
        );
    }

    /**
     * Deny action.
     *
     * @param Request $request HTTP request
     * @param Order   $order   Order entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/deny', name: 'order_deny', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function deny(Request $request, Order $order): Response
    {
        $form = $this->createForm(FormType::class, $order, [
                'method' => 'PUT',
                'action' => $this->generateUrl('order_deny', ['id' => $order->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->orderService->deny($order);

            $this->addFlash(
                'success',
                $this->translator->trans('message.denied_successfully')
            );

            return $this->redirectToRoute('order_index');
        }

        return $this->render(
            'order/confirm.html.twig',
            [
                'form' => $form->createView(),
                'order' => $order,
                ]
        );
    }

    /**
     * Return action.
     *
     * @param Request $request HTTP request
     * @param Order   $order   Order entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/return', name: 'order_return', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function return(Request $request, Order $order): Response
    {
        $form = $this->createForm(FormType::class, $order, [
                'method' => 'PUT',
                'action' => $this->generateUrl('order_return', ['id' => $order->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->orderService->return($order);

            $this->addFlash(
                'success',
                $this->translator->trans('message.returned_successfully')
            );

            return $this->redirectToRoute('order_index');
        }

        return $this->render(
            'order/confirm.html.twig',
            [
                'form' => $form->createView(),
                'order' => $order,
                ]
        );
    }

    /**
     * Show action.
     *
     * @param Order $order Order entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'order_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Order $order): Response
    {
        return $this->render(
            'order/show.html.twig',
            ['order' => $order]
        );
    }
}
