<?php

namespace PBlondeau\Bundle\SlideShowBundle\Controller\SlideShow;

use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class PublicController extends BaseController
{
    /**
     * @Route("/slides", name="slide_show_public_display")
     * @Template()
     */
    public function indexAction()
    {
        $criteria = array(
            'status' => BaseEntity::STATUS_ACTIVE
        );

        $order = array(
            'position' => 'ASC'
        );

        return array(
            'slides' => $this->getSlideRepository()->findBy($criteria, $order)
        );
    }
}
