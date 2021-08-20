<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanySearchType;
use App\Form\CompanyType;
use App\Form\ProcessSearchType;
use App\Form\StateSearchType;
use App\Repository\CompanyRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/companies")
 */
class CompanyController extends AbstractController
{
    private $em;
    private $states;

    public function __construct( EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->states = MenuController::renderMenu($this->em);
    }


    /**
     * @Route("/", name="company_index", methods={"GET", "POST"})
     */
    public function index(CompanyRepository $companyRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $search = new Company();
        $form = $this->createForm(CompanySearchType::class, $search);
        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $this->getDoctrine()->getManager()->getRepository(Company::class)->getAllQuery($search),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('company/index.html.twig', [
            'form' => $form->createView(),
            'states' => $this->states,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="company_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contacts = $company->getContacts();
            foreach ($contacts as $contact)
            {
                $contact->setCompany($company);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($company);
            $entityManager->flush();

            $this->addFlash(
                'sucess',
                'Company created with success'
            );

            return $this->redirectToRoute('company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('company/new.html.twig', [
            'states' => $this->states,
            'company' => $company,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="company_show", methods={"GET"})
     */
    public function show(Company $company): Response
    {
        return $this->render('company/show.html.twig', [
            'states' => $this->states,
            'company' => $company,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="company_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contacts = $company->getContacts();
            foreach ($contacts as $contact)
            {
                $contact->setCompany($company);
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'sucess',
                'Company updated with success'
            );

            return $this->redirectToRoute('company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('company/edit.html.twig', [
            'states' => $this->states,
            'company' => $company,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="company_delete", methods={"POST", "DELETE"})
     */
    public function delete(Request $request, Company $company): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            try{
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($company);
                $entityManager->flush();
                $this->addFlash(
                    'sucess',
                    'Company updated with success'
                );
            }
            catch (Exception $exception){
                $this->addFlash('alert', $exception->getMessage());
            }

        }

        return $this->redirectToRoute('company_index', [], Response::HTTP_SEE_OTHER);
    }
}
