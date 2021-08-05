<?php

Namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\CompanyProcess;
use App\Entity\Process;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


class CompanyProcessFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $cp = new CompanyProcess();
        $cp
            ->setCompany($manager->getRepository(Company::class)->findOneBy(["Name" => "Company"]))
            ->setProcess($manager->getRepository(Process::class)->findOneBy(["Name" => "Process 1"]))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setIsFinished(false);

         $manager->persist($cp);

        $manager->flush();
    }

    public function getOrder() {
        return 7;
    }
}
