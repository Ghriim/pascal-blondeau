<?php

namespace PBlondeau\Bundle\NewsBundle\Controller\News;

use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class PublicController extends BaseController
{
    /**
     * @Route("/news", name="news_public_display")
     * @Method("GET")
     */
    public function indexAction()
    {
        $criteria = array(
            'status' => BaseEntity::STATUS_ACTIVE
        );

        $order = array(
            'creation' => 'DESC'
        );

        $newsList = $this->getPaginator()->paginate(
            $this->getNewsRepository()->findBy($criteria, $order),
            $this->get('request')->query->get('page', 1),
            self::DEFAULT_ITEMS_PER_PAGE
        );

        return $this->render(
            'PBlondeauNewsBundle:News/Public:index.html.twig',
            array(
                'newsList' => $newsList
            ));
    }
}
