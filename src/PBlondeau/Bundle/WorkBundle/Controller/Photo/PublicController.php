<?php

namespace PBlondeau\Bundle\WorkBundle\Controller\Photo;

use PBlondeau\Bundle\WorkBundle\Entity\Album;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;
use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use Symfony\Component\HttpFoundation\Request;

class PublicController extends BaseController
{
    /**
     * @param Album   $album
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/albums/{id}/photos", name="work_album_photos_public_display")
     * @Route("/albums/{id}/photos/", name="work_album_photos_public_display_alias")
     * @Method("GET")
     */
    public function indexAction(Request $request, Album $album)
    {
        $criteria = array(
            'album'  => $album
        );

        $order = array(
            'position' => 'ASC'
        );

        $defaultPage = 1;
        $page = $request->get('page', $defaultPage);

        $photos = $this->getPaginator()->paginate(
            $this->getPhotoRepository()->findBy($criteria, $order),
            $page,
            self::DEFAULT_ITEMS_PER_PAGE
        );

        if ($page == $defaultPage) {
            $album->incrementOpenedCount();
            $this->getEntityManager()->flush();
        }

        return $this->render(
            'PBlondeauWorkBundle:Photo/Public:index.html.twig',
            array(
                'photos' => $photos
            ));
    }
}
