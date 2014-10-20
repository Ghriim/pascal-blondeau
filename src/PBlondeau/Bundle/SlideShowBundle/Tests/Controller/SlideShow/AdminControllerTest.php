<?php

namespace PBlondeau\Bundle\SlideShowBundle\Tests\Controller\SlideShow;

use PBlondeau\Bundle\CommonBundle\Tests\Controller\BaseWebTestCase;

class AdminControllerTest extends BaseWebTestCase
{
    public function testIndexForNotLoggedUser()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/admin/slides/');

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
    }

    public function testIndexForLoggedAdmin()
    {
        $client = $this->createAuthorizedClient();
        $client->enableProfiler();
        $client->request('GET', '/admin/slides/');

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }
}
