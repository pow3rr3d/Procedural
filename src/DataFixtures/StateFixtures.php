<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


class StateFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $state1 = new State();
        $state1->setName("In progress");

        $state2 = new State();
        $state2->setName("Finished");
        $state2->setIsFinalState(true);

        $states = [
            $state1,
            $state2
        ];


        foreach ($states as $state){
            $manager->persist($state);
        }


        $manager->flush();
    }

    public function getOrder() {
        return 4;
    }
}
