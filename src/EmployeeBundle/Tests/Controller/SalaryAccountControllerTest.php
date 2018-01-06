<?php

namespace EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SalaryAccountControllerTest extends WebTestCase
{
    public function testAllsalaryaccount()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/auth/all-salary-accounts');
    }

}
