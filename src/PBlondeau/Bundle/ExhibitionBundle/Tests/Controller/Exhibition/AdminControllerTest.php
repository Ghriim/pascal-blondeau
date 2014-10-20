<?php

namespace PBlondeau\Bundle\ExhibitionBundle\Tests\Controller\Exhibition;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/admin/exhibitions');

        $response = $client->getResponse();
        //$this->assertTrue($response->isSuccessful());
    }
}