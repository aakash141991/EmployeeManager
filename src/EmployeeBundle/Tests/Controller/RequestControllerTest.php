<?php

namespace EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RequestControllerTest extends WebTestCase
{
    public function testSeerequests()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/auth/view-requests');
    }

}
