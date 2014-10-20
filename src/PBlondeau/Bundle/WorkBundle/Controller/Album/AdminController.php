<?php

namespace PBlondeau\Bundle\WorkBundle\Controller\Album;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;
use PBlondeau\Bundle\WorkBundle\Entity\Album;
use PBlondeau\Bundle\WorkBundle\Form\Type\AlbumType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Album controller.
 *
 * @Route("/admin/albums")
 */
class AdminController extends BaseController
{

    /**
     * @Route("/", name="admin_work_albums")
     * @Method("GET")
     */
    public function indexAction()
    {
        $albums = $this->getPaginator()->paginate(
            $this->getAlbumRepository()->findForAdminList(),
            $this->get('request')->query->get('page', 1)
        );

        return $this->render(
            'PBlondeauWorkBundle:Album/Admin:index.html.twig',
            array(
                'albums' => $albums,
            ));
    }

    /**
     * @param Request $request
     * @param Album $album
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/create", name="admin_work_albums_create")
     * @Route("/{id}/update", name="admin_work_albums_edit")
     * @Method({"GET", "POST"})
     */
    public function saveAjaxAction(Request $request, Album $album = null)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedException('This path is only accessible in ajax');
        }

        if (!$album) {
            $album = new Album();
            $album->setUser($this->getUser());
        }

        $form = $this->buildSaveForm($album);

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getEntityManager()->persist($album);
                $this->getEntityManager()->flush();

                $context = $album ? 'update' : 'create';
                $message = $this->getSuccessMessageFromContext($context);
                $this->addFlashMessage($message, 'success');

                return $this->redirect($this->generateUrl('admin_work_albums'));
            }
        }

        return $this->render(
            'PBlondeauWorkBundle:Album/Admin:_saveForm.html.twig',
            array(
                'form' => $form->createView(),
            ));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/update-position", name="admin_work_albums_update_positions")
     * @Method("POST")
     */
    public function updatePositionAjaxAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedException('This path is only accessible in ajax');
        }

        $idWithPositionList = $request->get('idWithPositionList');
        foreach ($idWithPositionList as $idWithPosition) {
            /** @var Album $album */
            $album = $this->getAlbumRepository()->find($idWithPosition['id']);
            if (!$album) {
                throw new NotFoundHttpException();
            }
            $album->setPosition($idWithPosition['position']);
        }

        $this->getEntityManager()->flush();

        return new JsonResponse(
            array(
                'status' => 'success',
                'message' => $this->getTranslator()->trans('form.updatePosition.message', array(), 'adminWorkAlbum')
            )
        );
    }

    /**
     * @param Album $album
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="admin_work_albums_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Album $album)
    {
        $this->getEntityManager()->remove($album);
        $this->getEntityManager()->flush();

        $message = $this->getSuccessMessageFromContext('delete');
        $this->addFlashMessage($message, 'success');

        return $this->redirect($this->generateUrl('admin_work_albums'));
    }

    /**
     * @param Album $album
     *
     * @return \Symfony\Component\Form\Form
     */
    private function buildSaveForm(Album $album = null)
    {
        if ($album->isNew()) {
            $action = $this->generateUrl('admin_work_albums_create');
            $validationGroups = array('creation');
        } else {
            $action = $this->generateUrl('admin_work_albums_edit',
                array('id' => $album->getId()));

            $validationGroups = array('default');
        }

        return $this->createForm(new AlbumType(), $album, array(
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
        return $this->getTranslator()->trans('form.' . $context . '.success.message', array(), 'adminWorkAlbum');
    }

}
