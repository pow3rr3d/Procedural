<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\StateSearchType;
use App\Form\UserSearchType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    private $em;
    private $states;
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->states = MenuController::renderMenu($this->em);
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="account_index", methods={"GET", "POST"})
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $request->getSession()->replace(['_locale' => $this->getUser()->getLocale() ]);

            $this->addFlash(
                'sucess',
                $this->translator->trans('User updated with success', [], 'flashes')
            );

            return $this->redirectToRoute('account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('account/index.html.twig', [
            'states' => $this->states,
            'user' => $user,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/{id}", name="refresh_token", methods={"GET", "POST"})
     */
    public function refreshToken(Request $request, User $user): Response
    {
        $date = new \DateTime();
        $user->setApiToken(hash('sha256', ''.$user->getId().''.$date->format('Y-m-d H:i:s').''.$user->getEmail().''));
        $this->em->persist($user);
        $this->em->flush();

        return $this->redirectToRoute("account_index");
    }
}


