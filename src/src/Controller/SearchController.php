<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/search")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/index", name="search_index", methods={"POST", "GET"})
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function search(EntityManagerInterface $em)
    {
        $results[] = [
            "role" => [
                "value" => $this->getUser()->getRoles()
            ]
        ];

        $json = json_decode(file_get_contents('php://input'), true);

        if ($this->getUser()->getRoles() === ["ROLE_ADMIN"]) {
        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from('App\Entity\User', 'u')
            ->orwhere($qb->expr()->like('u.name', ':data'))
            ->orWhere($qb->expr()->like('u.surname', ':data'))
            ->orWhere($qb->expr()->like('u.email', ':data'))
            ->setParameter('data', '%' . $json["search"] . '%')
            ->orderBy('u.id', 'ASC');

        }

        $users = $qb->getQuery()->getResult();

        foreach ($users as $user) {
            $results[] = [
                "user" => [
                    "id" => $user->getId(),
                    "name" => $user->getName(),
                    "surname" => $user->getSurname(),
                    "email" => $user->getEmail(),
                ]
            ];
        }
        if ($this->getUser()->getRoles() === ["ROLE_ADMIN"]) {
            $qb = $em->createQueryBuilder();
            $qb->select('c')
                ->from('App\Entity\Company', 'c')
                ->orwhere($qb->expr()->like('c.name', ':data'))
                ->setParameter('data', '%' . $json["search"] . '%')
                ->orderBy('c.id', 'ASC');


            $companies = $qb->getQuery()->getResult();

            foreach ($companies as $company) {
                $results[] = [
                    "company" => [
                        "id" => $company->getId(),
                        "name" => $company->getName(),
                    ]
                ];
            }
        }

        if ($this->getUser()->getRoles() === ["ROLE_ADMIN"]) {
            $qb = $em->createQueryBuilder();
            $qb->select('p')
                ->from('App\Entity\Process', 'p')
                ->orwhere($qb->expr()->like('p.name', ':data'))
                ->setParameter('data', '%' . $json["search"] . '%')
                ->orderBy('p.id', 'ASC');


            $processes = $qb->getQuery()->getResult();

            foreach ($processes as $process) {
                $results[] = [
                    "process" => [
                        "id" => $process->getId(),
                        "name" => $process->getName(),
                    ]
                ];
            }
        }
        if ($this->getUser()->getRoles() === ["ROLE_ADMIN"]) {
            $qb = $em->createQueryBuilder();
            $qb->select('s')
                ->from('App\Entity\State', 's')
                ->orwhere($qb->expr()->like('s.name', ':data'))
                ->setParameter('data', '%' . $json["search"] . '%')
                ->orderBy('s.id', 'ASC');


            $states = $qb->getQuery()->getResult();

            foreach ($states as $state) {
                $results[] = [
                    "state" => [
                        "id" => $state->getId(),
                        "name" => $state->getName(),
                    ]
                ];
            }
        }

        $response = new Response(json_encode($results, JSON_UNESCAPED_UNICODE));

        return $response;
    }

}