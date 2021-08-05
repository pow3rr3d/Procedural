<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class CompanyFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $company = New Company();
        $company
            ->setName("Company");

        $manager->persist($company);

        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }
}
