<?php

namespace PBlondeau\Bundle\PressBundle\Controller\PressArticle;

use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class PublicController extends BaseController
{
    /**
     * @Route("/press", name="press_public_display")
     */
    public function indexAction()
    {
        $criteria = array(
            'status' => BaseEntity::STATUS_ACTIVE
        );

        $order = array(
            'position' => 'ASC'
        );

        $pressArticles = $this->getPaginator()->paginate(
            $this->getPressArticleRepository()->findBy($criteria, $order),
            $this->get('request')->query->get('page', 1)
        );

        return $this->render(
            'PBlondeauPressBundle:PressArticle/Public:index.html.twig',
            array(

                'pressArticles' => $pressArticles
        ));
    }
}
