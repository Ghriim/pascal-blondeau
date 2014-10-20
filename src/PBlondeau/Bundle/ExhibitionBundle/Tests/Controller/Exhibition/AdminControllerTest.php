<?php

namespace PBlondeau\Bundle\ExhibitionBundle\Tests\Controller\Exhibition;

use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use PBlondeau\Bundle\CommonBundle\Tests\Controller\BaseWebTestCase;
use PBlondeau\Bundle\ExhibitionBundle\Entity\Exhibition;

class AdminControllerTest extends BaseWebTestCase
{
    /**
     * @var Exhibition
     */
    private $exhibition;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $exhibitionRepository = static::$kernel->getContainer()->get('doctrine')->getRepository('PBlondeauExhibitionBundle:Exhibition');
        $this->exhibition =  $exhibitionRepository->findOneByStatus(BaseEntity::STATUS_ACTIVE);
    }

    public function testIndexForNotLoggedUser()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/admin/exhibitions/');

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect($this->generateUrl('fos_user_security_login', array(), true)));
    }

    public function testIndexForLoggedAdmin()
    {
        $client = $this->createAuthorizedClient();
        $client->enableProfiler();
        $client->request('GET', '/admin/exhibitions/');

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
    }

    public function testDeleteForNotLoggedUser()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('DELETE', '/admin/exhibitions/' . $this->exhibition->getId());

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect($this->generateUrl('fos_user_security_login', array(), true)));
    }

    public function testDeleteForLoggedAdmin()
    {
        $client = $this->createAuthorizedClient();
        $client->enableProfiler();
        $client->request('DELETE', '/admin/exhibitions/' . $this->exhibition->getId());

        $response = $client->getResponse();

        $this->assertTrue($response->isRedirect($this->generateUrl('admin_exhibitions')));
    }
}
