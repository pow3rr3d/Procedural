<?php

namespace App\DataFixtures;

use App\Entity\Process;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


class ProcessFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $process = new Process();
        $process
            ->setName("Process 1")
            ->setDescription("Descrpition form Process 1")
            ->setState($manager->getRepository(State::class)->findOneBy(["Name" => "En cours"]))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(null);

        $manager->persist($process);

        $manager->flush();
    }

    public function getOrder() {
        return 5;
    }
}
