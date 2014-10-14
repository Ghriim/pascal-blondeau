<?php

namespace PBlondeau\Bundle\PressBundle\Controller\PressArticle;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;
use PBlondeau\Bundle\PressBundle\Entity\PressArticle;
use PBlondeau\Bundle\PressBundle\Form\Type\PressArticleType;

/**
 * PressArticle controller.
 *
 * @Route("/admin/press")
 */
class AdminController extends BaseController
{

    /**
     * @Route("/", name="admin_press_articles")
     * @Method("GET")
     */
    public function indexAction()
    {
        $pressArticles = $this->getPaginator()->paginate(
            $this->getPressArticleRepository()->findForAdminList(),
            $this->get('request')->query->get('page', 1)
        );

        return $this->render(
            'PBlondeauPressBundle:PressArticle/Admin:index.html.twig',
            array(
                'pressArticles' => $pressArticles,
            ));
    }

    /**
     * @param Request $request
     * @param PressArticle $pressArticle
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/create", name="admin_press_articles_create")
     * @Route("/{id}/update", name="admin_press_articles_edit")
     * @Method({"GET", "POST"})
     */
    public function saveAjaxAction(Request $request, PressArticle $pressArticle = null)
    {
        if (!$pressArticle) {
            $pressArticle = new PressArticle();
            $pressArticle->setUser($this->getUser());
        }

        $form = $this->buildSaveForm($pressArticle);

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getEntityManager()->persist($pressArticle);
                $this->getEntityManager()->flush();

                $context = $pressArticle ? 'update' : 'create';
                $message = $this->getSuccessMessageFromContext($context);
                $this->addFlashMessage($message, 'success');

                return $this->redirect($this->generateUrl('admin_press_articles'));
            }
        }

        return $this->render(
            'PBlondeauPressBundle:PressArticle/Admin:index.html.twig',
            array(
                'form' => $form->createView(),
            ));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/update-position", name="admin_press_articles_update_positions")
     * @Method("POST")
     */
    public function updatePositionAjaxAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $idWithPositionList = $request->get('idWithPositionList');
            foreach ($idWithPositionList as $idWithPosition) {
                /** @var PressArticle $pressArticle */
                $pressArticle = $this->getPressArticleRepository()->find($idWithPosition['id']);
                if (!$pressArticle) {
                    throw new NotFoundHttpException();
                }
                $pressArticle->setPosition($idWithPosition['position']);
            }

            $this->getEntityManager()->flush();

            return new JsonResponse(
                array(
                    'status' => 'success',
                    'message' => $this->getTranslator()->trans('form.updatePosition.message', array(), 'adminPressArticle')
                )
            );
        }

        return new JsonResponse(array('status' => 'error'));
    }

    /**
     * @param PressArticle $pressArticle
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="admin_press_articles_delete")
     * @Method("DELETE")
     */
    public function deleteAction(PressArticle $pressArticle)
    {
        $this->getEntityManager()->remove($pressArticle);
        $this->getEntityManager()->flush();

        $message = $this->getSuccessMessageFromContext('delete');
        $this->addFlashMessage($message, 'success');

        return $this->redirect($this->generateUrl('admin_press_articles'));
    }

    /**
     * @param PressArticle $pressArticle
     *
     * @return \Symfony\Component\Form\Form
     */
    private function buildSaveForm(PressArticle $pressArticle = null)
    {
        if ($pressArticle->isNew()) {
            $action = $this->generateUrl('admin_press_articles_create');
            $validationGroups = array('creation');
        } else {
            $action = $this->generateUrl('admin_press_articles_edit',
                array('id' => $pressArticle->getId()));

            $validationGroups = array('default');
        }

        return $this->createForm(new PressArticleType(), $pressArticle, array(
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
        return $this->getTranslator()->trans('form.' . $context . '.success.message', array(), 'adminPressArticle');
    }

}
