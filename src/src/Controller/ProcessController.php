<?php

namespace App\Controller;

use App\Entity\Process;
use App\Form\ProcessType;
use App\Form\StepType;
use App\Repository\ProcessRepository;
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
    public function index(ProcessRepository $processRepository): Response
    {
        return $this->render('process/index.html.twig', [
            'processes' => $processRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="process_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $process = new Process();
        $form = $this->createForm(StepType::class, $process);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $process->setCreatedAt(new \DateTimeImmutable());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($process);
            $entityManager->flush();

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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($process);
            $entityManager->flush();
        }

        return $this->redirectToRoute('process_index', [], Response::HTTP_SEE_OTHER);
    }
}
