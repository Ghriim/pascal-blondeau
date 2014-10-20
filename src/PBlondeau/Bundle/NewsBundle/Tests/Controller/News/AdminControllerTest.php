<?php

namespace PBlondeau\Bundle\NewsBundle\Tests\Controller\News;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/admin/news');

        $response = $client->getResponse();
        //$this->assertTrue($response->isSuccessful());
    }
}