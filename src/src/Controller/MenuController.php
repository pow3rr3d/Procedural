<?php


namespace App\Controller;

use App\Entity\CompanyProcess;
use Doctrine\ORM\EntityManagerInterface;


class MenuController
{
    public static function renderMenu(EntityManagerInterface $em)
    {
        $states = [];
        $companyProcesses = $em->getRepository(CompanyProcess::class)->findAll();


        foreach ($companyProcesses as $companyProcess){
            $states[$companyProcess->getState()->getId()] = [$companyProcess->getState()->getName(), 0, "?s.state.Id=".$companyProcess->getState()->getId()];
        }
        foreach ($companyProcesses as $companyProcess){
            $states[$companyProcess->getState()->getId()]['1'] += 1;
        }

        return $states;


    }

}

