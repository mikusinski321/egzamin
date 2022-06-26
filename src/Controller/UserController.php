<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Service\UserServiceInterface;
use App\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController.
 */
#[Route('/users')]
class UserController extends AbstractController
{
    /**
     * User service.
     */
    private UserServiceInterface $userService;
    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param UserServiceInterface $userService User service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * ChangePassword action.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/changePassword', name: 'user_changePassword', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function changePassword(Request $request, User $user): Response
    {
        if ($this->getUser() === $user) {
            $form = $this->createForm(UserType::class, $user, [
                'method' => 'PUT',
                'action' => $this->generateUrl('user_changePassword', ['id' => $user->getId()]),
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->userService->save($user);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.changed_successfully')
                );

                return $this->redirectToRoute('item_index');
            }

            return $this->render(
                'user/change.html.twig',
                [
                    'form' => $form->createView(),
                    'user' => $user,
                ]
            );
        }
        $this->addFlash(
            'warning',
            $this->translator->trans('message.page_not_found')
        );

        return $this->redirectToRoute('item_index');
    }
}
