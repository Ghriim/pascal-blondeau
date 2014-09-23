<?php

namespace PBlondeau\Bundle\WorkBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class WorkController extends BaseController
{
    /**
     * @Route("/", name="work_public_display")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
