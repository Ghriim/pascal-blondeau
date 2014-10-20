<?php

namespace PBlondeau\Bundle\PressBundle\Tests\Controller\Press;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublicControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/press');

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }
}
