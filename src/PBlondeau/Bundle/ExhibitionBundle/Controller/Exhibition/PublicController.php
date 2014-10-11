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
        $criteria = array(
            'status' => BaseEntity::STATUS_ACTIVE
        );

        $order = array(
            'position' => 'ASC'
        );

        $exhibitions = $this->getPaginator()->paginate(
            $this->getExhibitionRepository()->findBy($criteria, $order),
            $this->get('request')->query->get('page', 1)
        );

        return array(
            'exhibitions' => $exhibitions
        );
    }
}
