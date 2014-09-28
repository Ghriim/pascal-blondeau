<?php

namespace PBlondeau\Bundle\WorkBundle\Controller\Album;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;
use PBlondeau\Bundle\SlideShowBundle\Entity\Slide;
use PBlondeau\Bundle\SlideShowBundle\Form\Type\SlideType;

/**
 * Slide controller.
 *
 * @Route("/admin/albums")
 */
class AdminController extends BaseController
{

    /**
     * @Route("/", name="admin_work_albums")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array(
        );
    }

    /**
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/", name="admin_albums_create")
     * @Method("POST")
     * @Template("PBlondeauWorkBundle:Album/Admin:index.html.twig")
     */
    public function createAction(Request $request)
    {
        return array(
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_albums_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {

    }

    /**
    * Creates a form to edit a Slide entity.
    *
    * @param Album $album
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Album $album)
    {

    }
    /**
     * @Route("/{id}", name="admin_albums_update")
     * @Method("PUT")
     * @Template("PBlondeauWorkBundle:Album/Admin:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return array(

        );
    }

    /**
     * @param Album $album
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="admin_albums_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Album $album)
    {
        $this->getEntityManager()->remove($album);
        $this->getEntityManager()->flush();

        $message = $this->getTranslator()->trans('form.delete.success', array(), 'adminWork');
        $this->addFlashMessage($message, 'success');

        return $this->redirect($this->generateUrl('admin_albums'));
    }
}
