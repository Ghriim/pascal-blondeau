<?php

namespace PBlondeau\Bundle\ExhibitionBundle\Controller\Exhibition;

use PBlondeau\Bundle\ExhibitionBundle\Form\Type\ExhibitionType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;
use PBlondeau\Bundle\ExhibitionBundle\Entity\Exhibition;

/**
 * Exhibition controller.
 *
 * @Route("/admin/exhibitions")
 */
class AdminController extends BaseController
{

    /**
     * @Route("/", name="admin_exhibitions")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $exhibitions = $this->getPaginator()->paginate(
            $this->getExhibitionRepository()->findForAdminList(),
            $this->get('request')->query->get('page', 1)
        );

        return array(
            'exhibitions' => $exhibitions,
        );
    }

    /**
     * @param Request $request
     * @param Exhibition $exhibition
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/create", name="admin_exhibitions_create")
     * @Route("/{id}/update", name="admin_exhibitions_edit")
     * @Method({"GET", "POST"})
     * @Template("PBlondeauExhibitionBundle:Exhibition/Admin:_saveForm.html.twig")
     */
    public function saveAjaxAction(Request $request, Exhibition $exhibition = null)
    {
        if (!$exhibition) {
            $exhibition = new Exhibition();
            $exhibition->setUser($this->getUser());
        }

        $form = $this->buildSaveForm($exhibition);

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getEntityManager()->persist($exhibition);
                $this->getEntityManager()->flush();

                $context = $exhibition ? 'update' : 'create';
                $message = $this->getSuccessMessageFromContext($context);
                $this->addFlashMessage($message, 'success');

                return $this->redirect($this->generateUrl('admin_exhibitions'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/update-position", name="admin_exhibitions_update_positions")
     * @Method("POST")
     */
    public function updatePositionAjaxAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $idWithPositionList = $request->get('idWithPositionList');
            foreach ($idWithPositionList as $idWithPosition) {
                /** @var Exhibition $exhibition */
                $exhibition = $this->getExhibitionRepository()->find($idWithPosition['id']);
                if (!$exhibition) {
                    throw new NotFoundHttpException();
                }
                $exhibition->setPosition($idWithPosition['position']);
            }

            $this->getEntityManager()->flush();

            return new JsonResponse(
                array(
                    'status' => 'success',
                    'message' => $this->getTranslator()->trans('form.updatePosition.message', array(), 'adminExhibition')
                )
            );
        }

        return new JsonResponse(array('status' => 'error'));
    }

    /**
     * @param Exhibition $exhibition
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="admin_exhibitions_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Exhibition $exhibition)
    {
        $this->getEntityManager()->remove($exhibition);
        $this->getEntityManager()->flush();

        $message = $this->getSuccessMessageFromContext('delete');
        $this->addFlashMessage($message, 'success');

        return $this->redirect($this->generateUrl('admin_exhibitions'));
    }

    /**
     * @param Exhibition $exhibition
     *
     * @return \Symfony\Component\Form\Form
     */
    private function buildSaveForm(Exhibition $exhibition = null)
    {
        if ($exhibition->isNew()) {
            $action = $this->generateUrl('admin_exhibitions_create');
            $validationGroups = array('creation');
        } else {
            $action = $this->generateUrl('admin_exhibitions_edit',
                array('id' => $exhibition->getId()));

            $validationGroups = array('default');
        }

        return $this->createForm(new ExhibitionType(), $exhibition, array(
            'action' => $action,
            'method' => 'POST',
            'validation_groups' => $validationGroups
        ));
    }

    /**
     * @param $context
     *
     * @return string
     */
    private function getSuccessMessageFromContext($context = 'create')
    {
        return $this->getTranslator()->trans('form.' . $context . '.success.message', array(), 'adminExhibition');
    }

}
