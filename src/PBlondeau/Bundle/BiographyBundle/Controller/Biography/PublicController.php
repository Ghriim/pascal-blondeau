<?php

namespace PBlondeau\Bundle\BiographyBundle\Controller\Biography;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class PublicController extends BaseController
{
    /**
     * @Route("/biography", name="biography_public_display")
     * @Template()
     */
    public function indexAction()
    {
        return array(

        );
    }
}
