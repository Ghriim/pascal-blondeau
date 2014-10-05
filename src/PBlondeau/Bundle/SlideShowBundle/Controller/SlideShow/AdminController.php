<?php

namespace PBlondeau\Bundle\SlideShowBundle\Controller\SlideShow;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            'form' => $form->createView(),
        );
    }

    /**
     * @param Request $request
     * @param Slide $slide
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/", name="admin_slides_create")
     * @Route("/{id}", name="admin_slides_edit")
     * @Method({"GET", "POST"})
     * @Template("PBlondeauSlideShowBundle:SlideShow/Admin:_saveForm.html.twig")
     */
    public function saveAjaxAction(Request $request, Slide $slide = null)
    {
        if (!$slide) {
            $slide = new Slide();
            $slide->setUser($this->getUser());

            $action = $this->generateUrl('admin_slides_create');
            $validationGroups = array('creation');
            $successMessageKey = 'form.modal.add.success.message';
        } else {
            $action = $this->generateUrl('admin_slides_edit',
                array('id' => $slide->getId()));

            $validationGroups = array('default');
            $successMessageKey = 'form.modal.edit.success.message';
        }

        $form = $this->createForm(new SlideType(), $slide, array(
            'action' => $action,
            'method' => 'POST',
            'validation_groups' => $validationGroups
        ));

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getEntityManager()->persist($slide);
                $this->getEntityManager()->flush();

                $message = $this->getTranslator()->trans($successMessageKey, array(), 'adminSlideShow');
                $this->addFlashMessage($message, 'success');

                return $this->redirect($this->generateUrl('admin_slides'));
            }
        }

        return array(
            'form' => $form->createView(),
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
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
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
