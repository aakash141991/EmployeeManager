<?php

namespace EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminManageAnnouncementControllerTest extends WebTestCase
{
    public function testAllannouncement()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/announcements');
    }

}
