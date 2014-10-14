<?php

namespace PBlondeau\Bundle\ExhibitionBundle\Controller\Exhibition;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class PublicController extends BaseController
{
    /**
     * @Route("/exhibitions", name="exhibition_public_display")
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

        return $this->render(
            'PBlondeauExhibitionBundle:Exhibition/Public:index.html.twig',
            array(
                'exhibitions' => $exhibitions
            ));
    }
}
