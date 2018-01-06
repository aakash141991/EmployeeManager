<?php

namespace EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeductionClaimControllerTest extends WebTestCase
{
    public function testClaimdeduction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/deduction-claim');
    }

}
