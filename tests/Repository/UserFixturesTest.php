<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserFixturesTest extends KernelTestCase
{
    public function getUser(){
        $container = static::getContainer();
        $user = $container->get(UserRepository::class)->findoneBy(['name' => 'Admin']);
        return $user;
    }

    public function testCount() {
        self::bootKernel();
        $container = static::getContainer();
        $user = $container->get(UserRepository::class)->count([]);
        $this->assertEquals(2, $user);
    }

    public function testValidUser(){
        self::bootKernel();
        $container = static::getContainer();
        $user = $this->getUser();
        $error = $container->get('validator')->validate($user);
        $this->assertCount(0, $error);
    }

    public function testInvalidUser(){
        self::bootKernel();
        $container = static::getContainer();
        $user = $this->getUser();
        $user->setRoles('');
        $error = $container->get('validator')->validate($user);
        $this->assertCount(1, $error);
    }

    public function testInvalidBlankName(){
        $this->assertHasErrors($this->getUser()->setName(null), 1);
    }
}