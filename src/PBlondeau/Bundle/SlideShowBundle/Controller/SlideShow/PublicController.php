<?php

namespace PBlondeau\Bundle\SlideShowBundle\Controller\SlideShow;

use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class PublicController extends BaseController
{
    /**
     * @Route("/slides", name="slide_show_public_display")
     */
    public function indexAction()
    {
        $criteria = array(
            'status' => BaseEntity::STATUS_ACTIVE
        );

        $order = array(
            'position' => 'ASC'
        );

        return $this->render(
            'PBlondeauSlideShowBundle:SlideShow/Public:index.html.twig',
            array(
                'slides' => $this->getSlideRepository()->findBy($criteria, $order)
        ));
    }
}
