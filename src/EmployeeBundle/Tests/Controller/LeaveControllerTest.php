<?php

namespace EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LeaveControllerTest extends WebTestCase
{
    public function testLeavehistory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/leave-history');
    }

}
