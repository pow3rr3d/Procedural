<?php

namespace App\Controller;

use App\Entity\CompanyProcess;
use App\Entity\CompanyProcessStep;
use App\Entity\State;
use App\Entity\Step;
use App\Form\CompanyProcessSearchType;
use App\Form\CompanyProcessStepsType;
use App\Form\ProcessSearchType;
use App\Repository\CompanyProcessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllProcessController extends AbstractController
{
    private $em;
    private $states;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->states = MenuController::renderMenu($this->em);
//        'states' => $this->states,
    }

    /**
     * @Route("/allprocess/", name="allprocess_index", methods={"GET", "POST"})
     * @param CompanyProcessRepository $companyProcess
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(CompanyProcessRepository $companyProcess, PaginatorInterface $paginator, Request $request): Response
    {
        $search = new CompanyProcess();
        $form = $this->createForm(CompanyProcessSearchType::class, $search);
        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $this->getDoctrine()->getManager()->getRepository(CompanyProcess::class)->getAllQuery($search),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('allprocess/index.html.twig', [
            'form' => $form->createView(),
            'states' => $this->states,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/allprocess/{slug}", name="allprocess_slug", methods={"GET", "POST"})
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param string $slug
     * @return Response
     */
    public function slug(PaginatorInterface $paginator, Request $request, string $slug): Response
    {
        $search = new CompanyProcess();
        $form = $this->createForm(CompanyProcessSearchType::class, $search);
        $form->handleRequest($request);

        $id = preg_replace("([^0-9]*)", "", $slug);

        $search->setState($this->getDoctrine()->getRepository(State::class)->findOneBy(["id" => $id]));

        $pagination = $paginator->paginate(
            $this->getDoctrine()->getManager()->getRepository(CompanyProcess::class)->getAllQuery($search),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('allprocess/index.html.twig', [
            'form' => $form->createView(),
            'states' => $this->states,
            'pagination' => $pagination,
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
        $form = $this->createForm(ProcessSearchType::class, $companyProcess);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyProcess->setCreatedAt(new \DateTimeImmutable());
            $companyProcess->setUpdatedAt(new \DateTimeImmutable());
            $companyProcess->setIsFinished(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($companyProcess);
            $entityManager->flush();

            $this->addFlash(
                'sucess',
                'Process created with success'
            );
            return $this->redirectToRoute('allprocess_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('companyprocess/new.html.twig', [
            'states' => $this->states,
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
            'states' => $this->states,
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
            $this->addFlash(
                'sucess',
                'Process already finished'
            );
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

            if (count($companyProcess->getCompanyProcessSteps()) + 1 === count($companyProcess->getProcess()->getSteps())) {
                $companyProcess->setUpdatedAt(new \DateTimeImmutable());
                $companyProcessStep = new CompanyProcessStep();
                $companyProcessStep
                    ->setCompanyProcess($companyProcess)
                    ->setStep($nextStep)
                    ->setValidatedAt(new \DateTimeImmutable())
                    ->setValidatedBy($this->getUser());

                $companyProcess->setIsFinished(true);
                $companyProcess->setState($this->getDoctrine()->getManager()->getRepository(State::class)->findOneBy(["IsFinalState" => true]));
                $this->getDoctrine()->getManager()->persist($companyProcess);
                $this->getDoctrine()->getManager()->persist($companyProcessStep);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'sucess',
                    'Process fishined with success'
                );
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
                    'states' => $this->states,
                    'id' => $companyProcess->getId()
                ]);
            }

        }

        return $this->renderForm('companyprocess/edit.html.twig', [
            'states' => $this->states,
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
                    $this->addFlash(
                        'sucess',
                        'Process deleted with success'
                    );
                } else {
                    $companyProcess->setIsSoftDeleted(true);
                    $this->addFlash(
                        'sucess',
                        'Process soft-deleted with success'
                    );
                }
            } else {
                $companyProcess->setIsSoftDeleted(true);
                $this->addFlash(
                    'sucess',
                    'Process soft-deleted with success'
                );
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

            $this->addFlash(
                'sucess',
                'Process restore with success'
            );
        }

        return $this->redirectToRoute('allprocess_index', [], Response::HTTP_SEE_OTHER);
    }

}
