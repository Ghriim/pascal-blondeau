<?php

namespace PBlondeau\Bundle\ExhibitionBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class ExhibitionController extends BaseController
{
    /**
     * @Route("/", name="exhibition_public_display")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
