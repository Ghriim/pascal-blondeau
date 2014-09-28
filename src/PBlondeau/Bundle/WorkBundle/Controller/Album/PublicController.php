<?php

namespace PBlondeau\Bundle\WorkBundle\Controller\Album;

use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class PublicController extends BaseController
{
    /**
     * @Route("/work", name="work_public_display")
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
            'albums' => null //$this->getAlbumRepository()->findBy($criteria, $order)
        );
    }
}
