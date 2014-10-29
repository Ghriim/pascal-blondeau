<?php

namespace PBlondeau\Bundle\WorkBundle\Controller\Album;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;
use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;

class PublicController extends BaseController
{
    /**
     * @Route("/albums", name="work_albums_public_display")
     */
    public function indexAction()
    {
        $criteria = array(
            'status' => BaseEntity::STATUS_ACTIVE
        );

        $order = array(
            'position' => 'ASC'
        );

        $albums = $this->getPaginator()->paginate(
            $this->getAlbumRepository()->findBy($criteria, $order),
            $this->get('request')->query->get('page', 1)
        );

        return $this->render(
            'PBlondeauWorkBundle:Album/Public:index.html.twig',
            array(
                'albums' => $albums
            ));
    }
}
