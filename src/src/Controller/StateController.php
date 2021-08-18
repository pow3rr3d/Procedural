<?php

namespace App\Controller;

use App\Entity\State;
use App\Form\StateType;
use App\Repository\StateRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/state")
 */
class StateController extends AbstractController
{
    private $em;
    private $states;

    public function __construct( EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->states = MenuController::renderMenu($this->em);
//        'states' => $this->states,
    }

    /**
     * @Route("/", name="state_index", methods={"GET"})
     */
    public function index(StateRepository $stateRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $search = new State();
        $pagination = $paginator->paginate(
            $this->getDoctrine()->getManager()->getRepository(State::class)->getAllQuery($search),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('state/index.html.twig', [
            'states' => $this->states,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="state_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $state = new State();
        $form = $this->createForm(StateType::class, $state);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get("IsFinalState")->getData() === false) {
                $state->setIsFinalState(null);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($state);
            $entityManager->flush();

            $this->addFlash(
                'sucess',
                'State created with success'
            );

            return $this->redirectToRoute('state_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('state/new.html.twig', [
            'states' => $this->states,
            'state' => $state,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="state_show", methods={"GET"})
     */
    public function show(State $state): Response
    {
        return $this->render('state/show.html.twig', [
            'states' => $this->states,
            'state' => $state,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="state_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, State $state): Response
    {
        $form = $this->createForm(StateType::class, $state);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get("IsFinalState")->getData() === false) {
                $state->setIsFinalState(null);
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'sucess',
                'State updated with success'
            );


            return $this->redirectToRoute('state_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('state/edit.html.twig', [
            'states' => $this->states,
            'state' => $state,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="state_delete", methods={"POST", "DELETE"})
     */
    public function delete(Request $request, State $state): Response
    {
        if ($this->isCsrfTokenValid('delete' . $state->getId(), $request->request->get('_token'))) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($state);
                $entityManager->flush();
                $this->addFlash(
                    'sucess',
                    'State deleted with success'
                );
            } catch (Exception $exception) {
                $this->addFlash('alert', $exception->getMessage());
            }
        }

        return $this->redirectToRoute('state_index', [], Response::HTTP_SEE_OTHER);
    }
}
