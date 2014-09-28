<?php

namespace PBlondeau\Bundle\NewsBundle\Controller\News;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

/**
 * News Admin controller.
 *
 * @Route("/admin/news")
 */
class AdminController extends BaseController
{

	/**
	 * @Route("/", name="admin_news")
	 * @Method("GET")
	 * @Template()
	 */
	public function indexAction()
	{
		return array(
		);
	}

}
