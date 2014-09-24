<?php

namespace PBlondeau\Bundle\NewsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class NewsController extends BaseController
{
    /**
     * @Route("/", name="news_public_display")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
