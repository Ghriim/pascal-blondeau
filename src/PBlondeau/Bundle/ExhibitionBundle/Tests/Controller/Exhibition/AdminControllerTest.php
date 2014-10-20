<?php

namespace PBlondeau\Bundle\ExhibitionBundle\Tests\Controller\Exhibition;

use PBlondeau\Bundle\CommonBundle\Tests\Controller\BaseWebTestCase;

class AdminControllerTest extends BaseWebTestCase
{
    public function testIndexForNotLoggedUser()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/admin/exhibitions/');

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
    }

    public function testIndexForLoggedAdmin()
    {
        $client = $this->createAuthorizedClient();
        $client->enableProfiler();
        $client->request('GET', '/admin/exhibitions/');

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    public function testDeleteForNotLoggedUser()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('DELETE', '/admin/exhibitions/1');

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
    }

    public function testDeleteForLoggedAdmin()
    {
        $client = $this->createAuthorizedClient();
        $client->enableProfiler();
        $client->request('DELETE', '/admin/exhibitions/1');

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }
}
