<?php

namespace PBlondeau\Bundle\WorkBundle\Tests\Controller\Album;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testIndexForNotLoggedUser()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/admin/albums');

        $response = $client->getResponse();
        $this->assertFalse($response->isSuccessful());
    }

    public function testIndexForLoggedAdmin()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/admin/albums');

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }
}