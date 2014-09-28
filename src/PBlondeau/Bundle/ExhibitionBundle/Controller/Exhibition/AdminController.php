<?php

namespace PBlondeau\Bundle\ExhibitionBundle\Controller\Exhibition;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

/**
 * Exhibition Admin controller.
 *
 * @Route("/admin/exhibitions")
 */
class AdminController extends BaseController
{

    /**
     * @Route("/", name="admin_exhibitions")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array(
        );
    }

}
