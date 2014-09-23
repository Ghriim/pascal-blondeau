<?php

namespace PBlondeau\Bundle\BiographyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class BiographyController extends BaseController
{
    /**
     * @Route("/", name="biography_public_display")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
