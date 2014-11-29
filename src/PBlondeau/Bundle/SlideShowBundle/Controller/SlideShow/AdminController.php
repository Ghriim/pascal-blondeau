<?php

namespace PBlondeau\Bundle\SlideShowBundle\Controller\SlideShow;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;
use PBlondeau\Bundle\SlideShowBundle\Entity\Slide;
use PBlondeau\Bundle\SlideShowBundle\Form\Type\SlideType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     */
    public function indexAction()
    {
        $slides = $this->getPaginator()->paginate(
            $this->getSlideRepository()->findForAdminList(),
            $this->get('request')->query->get('page', 1),
            self::DEFAULT_ITEMS_PER_PAGE
        );

        return $this->render(
            'PBlondeauSlideShowBundle:SlideShow/Admin:index.html.twig',
            array(
                'slides' => $slides,
            )
        );
    }

    /**
     * @param Request $request
     * @param Slide   $slide
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/create", name="admin_slides_create")
     * @Route("/{id}/update", name="admin_slides_edit")
     * @Method({"GET", "POST"})
     */
    public function saveAjaxAction(Request $request, Slide $slide = null)
    {
        if (!$slide) {
            $slide = new Slide();
            $slide->setUser($this->getUser());
        }

        $form = $this->buildSaveForm($slide);

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getEntityManager()->persist($slide);
                $this->getEntityManager()->flush();

                $context = $slide ? 'update' : 'create';
                $message = $this->getSuccessMessageFromContext($context);
                $this->addFlashMessage($message, 'success');

                return $this->redirect($this->generateUrl('admin_slides'));
            }
        }

        return $this->render(
            'PBlondeauSlideShowBundle:SlideShow/Admin:_saveForm.html.twig',
            array(
                'slide' => $slide,
                'form'  => $form->createView(),
            )
        );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/update-position", name="admin_slides_update_positions")
     * @Method("POST")
     */
    public function updatePositionAjaxAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedException('This path is only accessible in ajax');
        }

        $idWithPositionList = $request->get('idWithPositionList');
        foreach ($idWithPositionList as $idWithPosition) {
            /** @var Slide $slide */
            $slide = $this->getSlideRepository()->find($idWithPosition['id']);
            if (!$slide) {
                throw new NotFoundHttpException();
            }
            $slide->setPosition($idWithPosition['position']);
        }

        $this->getEntityManager()->flush();

        return new JsonResponse(
            array(
                'status'  => 'success',
                'message' => $this->getTranslator()->trans('form.updatePosition.message', array(), 'adminSlideShow')
            )
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

        $message = $this->getSuccessMessageFromContext('delete');
        $this->addFlashMessage($message, 'success');

        return $this->redirect($this->generateUrl('admin_slides'));
    }

    /**
     * @param Slide $slide
     *
     * @return \Symfony\Component\Form\Form
     */
    private function buildSaveForm(Slide $slide)
    {
        if ($slide->isNew()) {
            $action           = $this->generateUrl('admin_slides_create');
            $validationGroups = array('creation');
        } else {
            $action = $this->generateUrl(
                'admin_slides_edit',
                array('id' => $slide->getId())
            );

            $validationGroups = array('default');
        }

        return $this->createForm(
            new SlideType(), $slide, array(
                'action'            => $action,
                'attr'              => array('class' => 'form form-horizontal'),
                'method'            => 'POST',
                'validation_groups' => $validationGroups
            ),
            array('slide' => $slide)
        );
    }

    /**
     * @param $context
     *
     * @return string
     */
    private function getSuccessMessageFromContext($context = 'create')
    {
        return $this->getTranslator()->trans('form.' . $context . '.success.message', array(), 'adminSlideShow');
    }

}
