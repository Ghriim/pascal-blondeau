<?php

namespace PBlondeau\Bundle\SlideShowBundle\Tests\Controller\SlideShow;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/admin/slides');

        $response = $client->getResponse();
        //$this->assertTrue($response->isSuccessful());
    }
}