<?php

Namespace App\DataFixtures;

use App\Entity\Process;
use App\Entity\Step;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


class StepFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $step1 = new Step();
        $step2 = new Step();
        $step3 = new Step();

        $step1
            ->setTitle("Step 1")
            ->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed porttitor leo in libero venenatis venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nullam vestibulum lectus ut nunc consequat, at tincidunt. ")
            ->setHelper("Helper form step 1")
            ->setIsRequired(true)
            ->setProcess($manager->getRepository(Process::class)->findOneBy(["Name" => "Process 1"]))
            ->setWeight(1);

        $step2
            ->setTitle("Step 2")
            ->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed porttitor leo in libero venenatis venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nullam vestibulum lectus ut nunc consequat, at tincidunt. ")
            ->setHelper("Helper form step 2")
            ->setIsRequired(false)
            ->setProcess($manager->getRepository(Process::class)->findOneBy(["Name" => "Process 1"]))
            ->setWeight(2);

        $step3
            ->setTitle("Step 3")
            ->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed porttitor leo in libero venenatis venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nullam vestibulum lectus ut nunc consequat, at tincidunt. ")
            ->setHelper("Helper form step 3")
            ->setIsRequired(true)
            ->setProcess($manager->getRepository(Process::class)->findOneBy(["Name" => "Process 1"]))
            ->setWeight(3);

        $steps = [
            $step1,
            $step2,
            $step3
        ];


        foreach ($steps as $step){
            $manager->persist($step);
        }

        $manager->flush();
    }

    public function getOrder() {
        return 6;
    }
}
