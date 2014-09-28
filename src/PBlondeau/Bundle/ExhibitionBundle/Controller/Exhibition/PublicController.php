<?php

namespace PBlondeau\Bundle\ExhibitionBundle\Controller\Exhibition;

use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class PublicController extends BaseController
{
    /**
     * @Route("/exhibitions", name="exhibition_public_display")
     * @Template()
     */
    public function indexAction()
    {
        return array(

        );
    }
}
