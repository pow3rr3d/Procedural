<?php

namespace App\Controller;

use App\Entity\CompanyProcess;
use App\Entity\CompanyProcessStep;
use App\Entity\State;
use App\Entity\Step;
use App\Form\CompanyProcessStepsType;
use App\Form\CompanyProcessType;
use App\Repository\CompanyProcessRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllProcessController extends AbstractController
{
    /**
     * @Route("/allprocess", name="allprocess_index", methods={"GET"})
     * @param CompanyProcessRepository $companyProcess
     * @return Response
     */
    public function index(CompanyProcessRepository $companyProcess): Response
    {
        return $this->render('allprocess/index.html.twig', [
            'processes' => $companyProcess->findAll(),
        ]);
    }

    /**
     * @Route("/companyprocess/new", name="companyprocess_new", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $companyProcess = new CompanyProcess();
        $form = $this->createForm(CompanyProcessType::class, $companyProcess);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyProcess->setCreatedAt(new \DateTimeImmutable());
            $companyProcess->setUpdatedAt(new \DateTimeImmutable());
            $companyProcess->setIsFinished(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($companyProcess);
            $entityManager->flush();

            return $this->redirectToRoute('allprocess_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('company/new.html.twig', [
            'companyProcess' => $companyProcess,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/companyprocess/{id}", name="companyprocess_show", methods={"GET"})
     * @param CompanyProcess $companyProcess
     * @return Response
     */
    public function show(CompanyProcess $companyProcess): Response
    {
        return $this->render('companyprocess/show.html.twig', [
            'companyProcess' => $companyProcess,
        ]);
    }

    /**
     * @Route("/companyprocess/edit/{id}", name="companyprocess_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param CompanyProcess $companyProcess
     * @return Response
     */
    public function edit(Request $request, CompanyProcess $companyProcess): Response
    {
        if ($companyProcess->getState()->getIsFinalState() === true) {
            return $this->redirectToRoute("allprocess_index");
        }
        $Steps = $companyProcess->getCompanyProcessSteps();
        foreach ($Steps as $step) {
            $currentStep = $step->getStep();
        }

        if (isset($currentStep)) {
            $nextStep = $this->getDoctrine()->getRepository(Step::class)->findOneBy([
                'weight' => $currentStep->getWeight() + 1,
                'process' => $currentStep->getProcess()
            ]);
        } else {
            $nextStep = $this->getDoctrine()->getRepository(Step::class)->findOneBy([
                'weight' => 1,
                'process' => $companyProcess->getProcess()
            ]);

        }


        $form = $this->createForm(CompanyProcessStepsType::class, $nextStep);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $return = $form->get("Step")->getData();

            if (count($companyProcess->getCompanyProcessSteps())) {
                $companyProcess->setIsFinished(true);
                $companyProcess->setState($this->getDoctrine()->getManager()->getRepository(State::class)->findOneBy(["IsFinalState" => true]));
                return $this->redirectToRoute("allprocess_index");
            }

            if ($return !== null) {
                $companyProcess->setUpdatedAt(new \DateTimeImmutable());
                $companyProcessStep = new CompanyProcessStep();
                $companyProcessStep
                    ->setCompanyProcess($companyProcess)
                    ->setStep($nextStep)
                    ->setValidatedAt(new \DateTimeImmutable())
                    ->setValidatedBy($this->getUser());

                $this->getDoctrine()->getManager()->persist($companyProcessStep);
                $this->getDoctrine()->getManager()->flush();


                return $this->redirectToRoute('companyprocess_edit', [
                    'id' => $companyProcess->getId()
                ]);
            }

        }

        return $this->renderForm('companyprocess/edit.html.twig', [
            'companyProcess' => $companyProcess,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/companyprocess/delete/{id}", name="companyprocess_delete", methods={"POST", "DELETE"})
     * @param Request $request
     * @param CompanyProcess $companyProcess
     * @return Response
     */
    public function delete(Request $request, CompanyProcess $companyProcess): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($this->isCsrfTokenValid('delete' . $companyProcess->getId(), $request->request->get('_token'))) {
            if ($this->getUser()->getRoles()['0'] === "ROLE_ADMIN") {
                if ($companyProcess->getIsSoftDeleted() === true) {
                    $companyProcessSteps = $entityManager->getRepository(CompanyProcessStep::class)->findBy(["CompanyProcess" => $companyProcess]);
                    foreach ($companyProcessSteps as $companyProcessStep) {
                        $entityManager->remove($companyProcessStep);
                    }
                    $entityManager->remove($companyProcess);
                } else {
                    $companyProcess->setIsSoftDeleted(true);
                }
            } else {
                $companyProcess->setIsSoftDeleted(true);
            }
        }

        $entityManager->flush();
        return $this->redirectToRoute('allprocess_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/companyprocess/restore/{id}", name="companyprocess_rollback", methods={"GET"})
     * @param CompanyProcess $companyProcess
     * @return Response
     */
    public function rollback(CompanyProcess $companyProcess): Response
    {
        if ($this->getUser()->getRoles()['0'] === "ROLE_ADMIN") {
            $entityManager = $this->getDoctrine()->getManager();
            $companyProcess->setIsSoftDeleted(false);
            $entityManager->flush();
        }

        return $this->redirectToRoute('allprocess_index', [], Response::HTTP_SEE_OTHER);
    }

}
