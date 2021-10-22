<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $date = new \DateTime();
        $admin = new User();
        $admin
            ->setName("Admin")
            ->setSurname("Admin")
            ->setEmail("admin@demo.com")
            ->setRoles("ROLE_ADMIN")
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'admin'))
            ->setApiToken(hash('sha256', ''.$admin->getId().''.$date->format('Y-m-d H:i:s').''.$admin->getEmail().''))
            ->setLocale('fr');

        $user = new User();
        $user
            ->setName("User")
            ->setSurname("User")
            ->setEmail("user@demo.com")
            ->setRoles("ROLE_USER")
            ->setPassword($this->passwordEncoder->encodePassword($user, 'user'))
            ->setApiToken(hash('sha256', ''.$user->getId().''.$date->format('Y-m-d H:i:s').''.$user->getEmail().''))
            ->setLocale('en');


        $manager->persist($admin);
        $manager->persist($user);


        $manager->flush();
    }


    public function getOrder() {
        return 1;
    }
}
