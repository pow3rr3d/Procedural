<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ContactFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $contact = new Contact();
        $contact
            ->setName("Contact")
            ->setSurname("Contact")
            ->setEmail("contact@contact.fr")
            ->setCompany($manager->getRepository(Company::class)->findOneBy(["name" => "Company"]))
            ->setMobile("06 51 51 51 51")
            ->setTel(null);

        $manager->persist($contact);

        $manager->flush();
    }

    public function getOrder() {
        return 3;
    }
}
