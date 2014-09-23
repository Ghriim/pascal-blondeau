<?php

namespace PBlondeau\Bundle\CommonBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="default_home")
     * @Template()
     */
    public function homeAction()
    {
        return array();
    }
}
