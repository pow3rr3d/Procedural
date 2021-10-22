<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

//This is an example of the way that we have to test an Entity
class UserRepositoryTest extends KernelTestCase
{
    //Create a new entity for testing
    public function getUser(){
        return (new User())
            ->setName('test')
            ->setSurname('test')
            ->setEmail('test@test.com')
            ->setRoles('Admin')
            ->setApiToken('1234')
            ->setLocale('fr');

    }

    //declare de way that you will make the tests
    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $container = static::getContainer();
        $errors = $container->get('validator')->validate($user);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    //Call the function of test for a valid entity
    public function testValidUser(){
        $this->assertHasErrors($this->getUser(), 0);
    }

    //Call the function of test for a invalid entity
    public function testInvalidUser(){
        $this->assertHasErrors(new User(), 1);
    }

    //Call the function of test to test that the "not blank" assert works on an entity
    //have to repeat it for each "not blank" assert in entity
    public function testInvalidBlankRoles(){
        $this->assertHasErrors($this->getUser()->setRoles(''), 1);
    }

    //Call the function of test to test that the "unique" assert works on an entity
    //have to repeat it for each "unique" assert in entity
    public function testInvalidUsedEmail ()
    {
        $this->assertHasErrors($this->getUser()->setEmail('admin@demo.com'), 1);
    }
}