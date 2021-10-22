<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

//This is an example of the way that we have to test a Controller
class UserControllerTest extends WebTestCase
{
    //Method to test the redirect of a route
    /** @test */
    public function TestRedirectResponse(){
        $client= static::createClient();
        $client->request('GET', '/user');
        $this->assertResponseRedirects('/login');
    }

    //Method to test the response of a route
    /** @test */
    public function TestResponse(){
        $client= static::createClient();
        $client->request('GET', '/login');
        //HTTP Response can be change depending on your expectations
        //ex: 200, 401, 404...
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    //Method to test the content of a page
    /** @test */
    public function TestExistTable(){
        $client= static::createClient();
        $client->request('GET', '/users');
        //HTTP Response can be change depending on your expectations
        //ex: 200, 401, 404...
        $this->assertSelectorExists('table');
    }

}