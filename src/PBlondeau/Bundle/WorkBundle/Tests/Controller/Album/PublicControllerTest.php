<?php

namespace PBlondeau\Bundle\WorkBundle\Tests\Controller\Album;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublicControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/albums');

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
    }
}
