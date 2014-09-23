<?php

namespace PBlondeau\Bundle\ContactBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class ContactController extends BaseController
{
    /**
     * @Route("/", name="contact_public_display")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
