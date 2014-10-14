<?php

namespace PBlondeau\Bundle\WorkBundle\Controller\Album;

use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class PublicController extends BaseController
{
    /**
     * @Route("/albums", name="work_albums_public_display")
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

        $albums = $this->getPaginator()->paginate(
            $this->getAlbumRepository()->findBy($criteria, $order),
            $this->get('request')->query->get('page', 1)
        );

        return array(
            'albums' => $albums
        );
    }
}
