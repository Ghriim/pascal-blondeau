<?php

namespace PBlondeau\Bundle\PressBundle\Controller\Press;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

/**
 * Press Admin controller.
 *
 * @Route("/admin/press")
 */
class AdminController extends BaseController
{

	/**
	 * @Route("/", name="admin_press")
	 * @Method("GET")
	 * @Template()
	 */
	public function indexAction()
	{
		return array(
		);
	}

}
