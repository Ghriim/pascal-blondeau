<?php

namespace PBlondeau\Bundle\WorkBundle\Controller\Photo;

use PBlondeau\Bundle\WorkBundle\Entity\Album;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;
use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;

class PublicController extends BaseController
{
    /**
     * @param Album $album
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/albums/{id}/photos", name="work_album_photos_public_display")
     */
    public function indexAction(Album $album)
    {
        $criteria = array(
            'album'  => $album,
            'status' => BaseEntity::STATUS_ACTIVE
        );

        $order = array(
            'position' => 'ASC'
        );

        $albums = $this->getPaginator()->paginate(
            $this->getPhotoRepository()->findBy($criteria, $order),
            $this->get('request')->query->get('page', 1)
        );

        return $this->render(
            'PBlondeauWorkBundle:Photo/Public:index.html.twig',
            array(
                'albums' => $albums
            ));
    }
}
