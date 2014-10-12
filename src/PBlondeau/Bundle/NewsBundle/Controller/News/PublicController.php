<?php

namespace PBlondeau\Bundle\NewsBundle\Controller\News;

use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class PublicController extends BaseController
{
    /**
     * @Route("/news", name="news_public_display")
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

        $newsList = $this->getPaginator()->paginate(
            $this->getNewsRepository()->findBy($criteria, $order),
            $this->get('request')->query->get('page', 1)
        );

        return array(
            'newsList' => $newsList
        );
    }
}
