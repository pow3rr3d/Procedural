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
        $user = new User();
        $user
            ->setName("Admin")
            ->setSurname("Admin")
            ->setEmail("admin@admin.fr")
            ->setRoles("ROLE_ADMIN")
            ->setPassword($this->passwordEncoder->encodePassword($user, 'admin'));

        $manager->persist($user);


        $manager->flush();
    }


    public function getOrder() {
        return 1;
    }
}
