<?php

namespace PBlondeau\Bundle\ExhibitionsBundle\Tests\Controller\Exhibition;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublicControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/exhibitions');

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
    }
}
