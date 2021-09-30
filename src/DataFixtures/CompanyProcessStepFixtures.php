<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\CompanyProcess;
use App\Entity\CompanyProcessStep;
use App\Entity\Step;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class CompanyProcessStepFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $cps = new CompanyProcessStep();
        $cps
            ->setCompanyProcess($manager->getRepository(CompanyProcess::class)->findOneBy(["company" => $manager->getRepository(Company::class)->findOneBy(["name" => "Company"])]))
            ->setStep($manager->getRepository(Step::class)->findOneBy(["title" => "Step 1"]))
            ->setValidatedAt(new \DateTimeImmutable())
            ->setValidatedBy($manager->getRepository(User::class)->findOneBy(["name" => "Admin"]));

        $manager->persist($cps);

        $manager->flush();
    }
    public function getOrder() {
        return 8;
    }

}
