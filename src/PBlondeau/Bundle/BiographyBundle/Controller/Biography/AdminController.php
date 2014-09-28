<?php

namespace PBlondeau\Bundle\BiographyBundle\Controller\Biography;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

/**
 * Biography Admin controller.
 *
 * @Route("/admin/biography")
 */
class AdminController extends BaseController
{

    /**
     * @Route("/", name="admin_biography")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array(
        );
    }

}
