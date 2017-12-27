<?php

namespace EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testUpdatedepartments()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/update-department');
    }

    public function testUpdateroles()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/update-roles');
    }

    public function testUpdatedesignation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/update-designation');
    }

    public function testUpdateleaves()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/update-leaves');
    }

}
