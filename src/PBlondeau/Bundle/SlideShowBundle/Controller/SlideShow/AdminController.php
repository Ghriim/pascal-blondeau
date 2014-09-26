<?php

namespace PBlondeau\Bundle\SlideShowBundle\Controller\SlideShow;

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
 * @Route("/admin/slides")
 */
class AdminController extends BaseController
{

    /**
     * @Route("/", name="admin_slides")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $slides = $this->getPaginator()->paginate(
            $this->getSlideRepository()->findForAdminList(),
            $this->get('request')->query->get('page', 1)
        );

        $slide = new Slide();
        $form = $this->createForm(new SlideType(), $slide, array(
            'action' => $this->generateUrl('admin_slides_create'),
            'method' => 'POST',
        ));

        return array(
            'slides' => $slides,
            'form'   => $form->createView(),
        );
    }

    /**
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/", name="admin_slides_create")
     * @Method("POST")
     * @Template("PBlondeauSlideShowBundle:SlideShow/Admin:list.html.twig")
     */
    public function createAction(Request $request)
    {
        $slide = new Slide();
        $slide->setUser($this->getUser());

        $form = $this->createForm(new SlideType(), $slide, array(
            'action' => $this->generateUrl('admin_slides_create'),
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEntityManager()->persist($slide);
            $this->getEntityManager()->flush();

            return $this->redirect($this->generateUrl('admin_slides'));
        }

        return array(
            'form'   => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_slides_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PBlondeauSlideShowBundle:Slide')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slide entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Slide entity.
    *
    * @param Slide $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Slide $entity)
    {
        $form = $this->createForm(new SlideType(), $entity, array(
            'action' => $this->generateUrl('admin_slides_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * @Route("/{id}", name="admin_slides_update")
     * @Method("PUT")
     * @Template("PBlondeauSlideShowBundle:SlideShow/Admin:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PBlondeauSlideShowBundle:Slide')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slide entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_slides'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * @param Slide $slide
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="admin_slides_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Slide $slide)
    {
        $this->getEntityManager()->remove($slide);
        $this->getEntityManager()->flush();

        $message = $this->getTranslator()->trans('form.delete.success', array(), 'adminSlideShow');
        $this->addFlashMessage($message, 'success');

        return $this->redirect($this->generateUrl('admin_slides'));
    }
}
