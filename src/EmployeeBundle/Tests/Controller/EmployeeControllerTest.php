<?php

namespace EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeeControllerTest extends WebTestCase
{
    public function testAddnewemployee()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/add-new-employee');
    }

    public function testDeleteemployee()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/delete-employee');
    }

    public function testUpdateemployee()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/update-employee');
    }

    public function testGetallemployees()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/get-all-Employees');
    }

}
