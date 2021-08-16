<?php

namespace App\Controller;

use App\Entity\Process;
use App\Form\ProcessType;
use App\Form\StepType;
use App\Repository\ProcessRepository;
use Doctrine\DBAL\Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/processes")
 */
class ProcessController extends AbstractController
{
    /**
     * @Route("/", name="process_index", methods={"GET"})
     */
    public function index(ProcessRepository $processRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $search = new Process();
        $pagination = $paginator->paginate(
            $this->getDoctrine()->getManager()->getRepository(Process::class)->getAllQuery($search),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('process/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    /**
     * @Route("/new", name="process_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $process = new Process();
        $form = $this->createForm(ProcessType::class, $process);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $process->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($process);
            $entityManager->flush();

            $this->addFlash(
                'sucess',
                'Process created with success'
            );

            return $this->redirectToRoute('process_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('process/new.html.twig', [
            'process' => $process,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="process_show", methods={"GET"})
     */
    public function show(Process $process): Response
    {
        return $this->render('process/show.html.twig', [
            'process' => $process,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="process_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Process $process): Response
    {
        $form = $this->createForm(ProcessType::class, $process);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getData()->getSteps() as $step){
                $step->setProcess($process);
            }
            $process->setUpdatedAt(new \DateTimeImmutable());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'sucess',
                'Process updated with success'
            );

            return $this->redirectToRoute('process_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('process/edit.html.twig', [
            'process' => $process,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="process_delete", methods={"GET", "DELETE"})
     */
    public function delete(Request $request, Process $process): Response
    {
        if ($this->isCsrfTokenValid('delete' . $process->getId(), $request->request->get('_token'))) {
            try{
                $entityManager = $this->getDoctrine()->getManager();
                $steps= $process->getSteps();
                foreach ($steps as $step){
                    $entityManager->remove($step);
                }
                $entityManager->remove($process);
                $entityManager->flush();
                $this->addFlash(
                    'sucess',
                    'Process deleted with success'
                );
            }
            catch (Exception $exception){
                $this->addFlash('alert', $exception->getMessage());
            }

        }

        return $this->redirectToRoute('process_index', [], Response::HTTP_SEE_OTHER);
    }
}
