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

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    private $em;
    private $states;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->states = MenuController::renderMenu($this->em);
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

            $this->addFlash(
                'sucess',
                'User updated with success'
            );

            return $this->redirectToRoute('account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('account/index.html.twig', [
            'states' => $this->states,
            'user' => $user,
            'form' => $form,
        ]);
    }

}


