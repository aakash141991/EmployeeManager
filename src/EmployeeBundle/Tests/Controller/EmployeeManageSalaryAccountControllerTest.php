<?php

namespace EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeeManageSalaryAccountControllerTest extends WebTestCase
{
    public function testAllsalaryaccounts()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/all-salary-accounts');
    }

}
