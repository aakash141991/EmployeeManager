<?php

namespace EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaymentControllerTest extends WebTestCase
{
    public function testAccountdetails()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/auth/accountDetails');
    }

}
