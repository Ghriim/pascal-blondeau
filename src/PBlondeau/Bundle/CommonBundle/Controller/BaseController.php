<?php

namespace PBlondeau\Bundle\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    const DEFAULT_ITEMS_PER_PAGE = 25;

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return \Symfony\Component\Security\Core\SecurityContext
     */
    protected function getSecurityContext()
    {
        return $this->get("security.context");
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    protected function getSession()
    {
        return $this->get('session');
    }

    /**
     * @return \Symfony\Component\Translation\Translator
     */
    protected function getTranslator()
    {
        return $this->get('translator');
    }

    /**
     * @return \Knp\Component\Pager\Paginator
     */
    protected function getPaginator()
    {
        return $this->get('knp_paginator');
    }

    /**
     * @param string $message
     * @param string $type
     */
    protected function addFlashMessage($message, $type = 'notice')
    {
        $this->getSession()->getFlashBag()->add($type, $message);
    }

    /**
     * @return \PBlondeau\Bundle\SlideShowBundle\Repository\SlideRepository
     */
    protected function getSlideRepository()
    {
        return $this->getEntityManager()->getRepository('PBlondeauSlideShowBundle:Slide');
    }
} 