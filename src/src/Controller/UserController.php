<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\StateSearchType;
use App\Form\UserSearchType;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTime;
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
 * @Route("/user")
 */
class UserController extends AbstractController
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
     * @Route("/", name="user_index", methods={"GET", "POST"})
     */
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $search = new User();
        $form = $this->createForm(UserSearchType::class, $search);
        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $this->getDoctrine()->getManager()->getRepository(User::class)->getAllQuery($search),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
            'states' => $this->states,
            'pagination' => $pagination,

        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $date = new DateTime();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPassword() === null) {
                $this->addFlash(
                    "string",
                    $this->translator->trans('Password required!', [], 'flashes')
                );
                $this->redirectToRoute("user_new");
            } else {
                $encoded = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($encoded);
                $user->setRoles("ROLE_USER");
                $user->setApiToken(hash('sha256', '' . $user->getId() . '' . $date->format('Y-m-d H:i:s') . '' . $user->getEmail() . ''));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash(
                    'sucess',
                    $this->translator->trans('User created with success', [], 'flashes')
                );

                return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('user/new.html.twig', [
            'states' => $this->states,
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'states' => $this->states,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'sucess',
                $this->translator->trans('User updated with success', [], 'flashes')
            );

            $request->getSession()->replace(['_locale' => $this->getUser()->getLocale() ]);

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'states' => $this->states,
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST", "DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($user);
                $entityManager->flush();
                $this->addFlash(
                    'sucess',
                    $this->translator->trans('User deleted with success', [], 'flashes')
                );
            } catch (Exception $exception) {
                $this->addFlash('alert', $exception->getMessage());
            }
        }


        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/admin/{id}", name="user_admin", methods={"PUT"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function setAdmin(Request $request, User $user, EntityManagerInterface $em)
    {
        if ($this->isCsrfTokenValid('update' . $user->getId(), $request->request->get('_token'))) {
            $user->setRoles("ROLE_ADMIN");
            $em->flush();
        }
        return $this->redirectToRoute('user_show', ["id" => $user->getId()]);
    }

    /**
     * @Route("/user/{id}", name="user_user", methods={"PUT"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function setUser(Request $request, User $user, EntityManagerInterface $em)
    {
        if ($this->isCsrfTokenValid('update' . $user->getId(), $request->request->get('_token'))) {
            $user->setRoles("ROLE_USER");
            $em->flush();
        }
        return $this->redirectToRoute('user_show', ["id" => $user->getId()]);
    }

    /**
     * @Route("/language/{id}", name="user_language", methods={"GET"})
     * @return Response
     */
    public function language(User $user)
    {
        return new Response(json_encode($user->getLocale(), JSON_UNESCAPED_UNICODE));
    }

}
