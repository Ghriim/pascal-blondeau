<?php

namespace PBlondeau\Bundle\PressBundle\Tests\Controller\Press;

use PBlondeau\Bundle\CommonBundle\Tests\Controller\BaseWebTestCase;

class AdminControllerTest extends BaseWebTestCase
{
    public function testIndexForNotLoggedUser()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/admin/press/');

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
    }

    public function testIndexForLoggedAdmin()
    {
        $client = $this->createAuthorizedClient();
        $client->enableProfiler();
        $client->request('GET', '/admin/press/');

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }
}
