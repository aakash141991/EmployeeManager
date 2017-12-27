<?php

namespace EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AssetControllerTest extends WebTestCase
{
    public function testAllassets()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/auth/allAssets');
    }

}
