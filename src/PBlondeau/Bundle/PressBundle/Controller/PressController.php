<?php

namespace PBlondeau\Bundle\PressBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class PressController extends BaseController
{
    /**
     * @Route("/", name="press_public_display")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
