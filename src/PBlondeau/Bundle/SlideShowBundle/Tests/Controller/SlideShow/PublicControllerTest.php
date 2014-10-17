<?php

namespace PBlondeau\Bundle\SlideShowBundle\Tests\Controller\SlideShow;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublicControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/slides');

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }
} 