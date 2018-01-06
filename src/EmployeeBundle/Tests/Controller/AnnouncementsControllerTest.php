<?php

namespace EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnnouncementsControllerTest extends WebTestCase
{
    public function testAllannouncement()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/announcements');
    }

    public function testAdminanouncements()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/announcements');
    }

}
