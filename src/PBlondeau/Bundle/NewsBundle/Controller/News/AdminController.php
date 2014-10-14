<?php

namespace PBlondeau\Bundle\NewsBundle\Controller\News;

use PBlondeau\Bundle\NewsBundle\Form\Type\NewsType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;
use PBlondeau\Bundle\NewsBundle\Entity\News;

/**
 * News controller.
 *
 * @Route("/admin/news")
 */
class AdminController extends BaseController
{

    /**
     * @Route("/", name="admin_news")
     * @Method("GET")
     */
    public function indexAction()
    {
        $newsList = $this->getPaginator()->paginate(
            $this->getNewsRepository()->findForAdminList(),
            $this->get('request')->query->get('page', 1)
        );

        return $this->render(
            'PBlondeauNewsBundle:News/Admin:index.html.twig',
            array(
                'newsList' => $newsList,
            ));
    }

    /**
     * @param Request $request
     * @param News $news
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/create", name="admin_news_create")
     * @Route("/{id}/update", name="admin_news_edit")
     * @Method({"GET", "POST"})
     */
    public function saveAjaxAction(Request $request, News $news = null)
    {
        if (!$news) {
            $news = new News();
            $news->setUser($this->getUser());
        }

        $form = $this->buildSaveForm($news);

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getEntityManager()->persist($news);
                $this->getEntityManager()->flush();

                $context = $news ? 'update' : 'create';
                $message = $this->getSuccessMessageFromContext($context);
                $this->addFlashMessage($message, 'success');

                return $this->redirect($this->generateUrl('admin_news'));
            }
        }

        return $this->render(
            'PBlondeauNewsBundle:News/Admin:_saveForm.html.twig',
            array(
                'form' => $form->createView(),
            ));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/update-position", name="admin_news_update_positions")
     * @Method("POST")
     */
    public function updatePositionAjaxAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $idWithPositionList = $request->get('idWithPositionList');
            foreach ($idWithPositionList as $idWithPosition) {
                /** @var News $news */
                $news = $this->getNewsRepository()->find($idWithPosition['id']);
                if (!$news) {
                    throw new NotFoundHttpException();
                }
                $news->setPosition($idWithPosition['position']);
            }

            $this->getEntityManager()->flush();

            return new JsonResponse(
                array(
                    'status' => 'success',
                    'message' => $this->getTranslator()->trans('form.updatePosition.message', array(), 'adminNews')
                )
            );
        }

        return new JsonResponse(array('status' => 'error'));
    }

    /**
     * @param News $news
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="admin_news_delete")
     * @Method("DELETE")
     */
    public function deleteAction(News $news)
    {
        $this->getEntityManager()->remove($news);
        $this->getEntityManager()->flush();

        $message = $this->getSuccessMessageFromContext('delete');
        $this->addFlashMessage($message, 'success');

        return $this->redirect($this->generateUrl('admin_news'));
    }

    /**
     * @param News $news
     *
     * @return \Symfony\Component\Form\Form
     */
    private function buildSaveForm(News $news = null)
    {
        if ($news->isNew()) {
            $action = $this->generateUrl('admin_news_create');
            $validationGroups = array('creation');
        } else {
            $action = $this->generateUrl('admin_news_edit',
                array('id' => $news->getId()));

            $validationGroups = array('default');
        }

        return $this->createForm(new NewsType(), $news, array(
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
        return $this->getTranslator()->trans('form.' . $context . '.success.message', array(), 'adminNews');
    }

}
