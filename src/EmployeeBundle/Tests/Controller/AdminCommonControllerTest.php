<?php

namespace EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminCommonControllerTest extends WebTestCase
{
    public function testApproveleave()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/approve-leave');
    }

    public function testApproveasset()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/approve-asset');
    }

    public function testGeneratepayslip()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/generate-payslip');
    }

    public function testServerequest()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/serve-request');
    }

}
