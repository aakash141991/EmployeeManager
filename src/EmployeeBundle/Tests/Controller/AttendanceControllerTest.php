<?php

namespace EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AttendanceControllerTest extends WebTestCase
{
    public function testManageattendance()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/manage-attendance');
    }

}
