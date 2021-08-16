<?php

namespace App\Controller;

use App\Entity\User;
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
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $search = new User();
        $pagination = $paginator->paginate(
            $this->getDoctrine()->getManager()->getRepository(User::class)->getAllQuery($search),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,

        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPassword() === null){
                $this->addFlash("string", "Password required!");
                $this->redirectToRoute("user_new");
            }
            else{
                $encoded = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($encoded);
                $user->setRoles("ROLE_USER");
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash(
                    'sucess',
                    'User created with success'
                );

                return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('user/new.html.twig', [
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
                'User updated with success'
            );

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST", "DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            try{
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($user);
                $entityManager->flush();
                $this->addFlash(
                    'sucess',
                    'User deleted with success'
                );
            }
            catch (Exception $exception){
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
}
