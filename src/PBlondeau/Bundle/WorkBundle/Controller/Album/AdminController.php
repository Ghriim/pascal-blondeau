<?php

namespace PBlondeau\Bundle\WorkBundle\Controller\Album;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;
use PBlondeau\Bundle\WorkBundle\Entity\Album;
use PBlondeau\Bundle\WorkBundle\Form\Type\AlbumType;

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
     * @Template()
     */
    public function indexAction()
    {
        $albums = $this->getPaginator()->paginate(
            $this->getAlbumRepository()->findForAdminList(),
            $this->get('request')->query->get('page', 1)
        );

        return array(
            'albums' => $albums,
        );
    }

    /**
     * @param Request $request
     * @param Album $album
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/create", name="admin_work_albums_create")
     * @Route("/{id}/update", name="admin_work_albums_edit")
     * @Method({"GET", "POST"})
     * @Template("PBlondeauWorkBundle:Album/Admin:_saveForm.html.twig")
     */
    public function saveAjaxAction(Request $request, Album $album = null)
    {
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

        return array(
            'form' => $form->createView(),
        );
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
        if ($request->isMethod('POST')) {
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

        return new JsonResponse(array('status' => 'error'));
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
