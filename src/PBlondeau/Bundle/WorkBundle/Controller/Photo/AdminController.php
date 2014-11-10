<?php

namespace PBlondeau\Bundle\WorkBundle\Controller\Photo;

use PBlondeau\Bundle\WorkBundle\Entity\Album;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;
use PBlondeau\Bundle\WorkBundle\Entity\Photo;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Photo controller.
 *
 * @Route("/admin/albums")
 */
class AdminController extends BaseController
{

    /**
     * @param Album $album
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{id}", name="admin_work_album_photos")
     * @Method("GET")
     */
    public function indexAction(Album $album)
    {
        $photos = $this->getPaginator()->paginate(
            $this->getPhotoRepository()->findForAdminList($album),
            $this->get('request')->query->get('page', 1)
        );

        return $this->render(
            'PBlondeauWorkBundle:Photo/Admin:index.html.twig',
            array(
                'album'  => $album,
                'photos' => $photos,
            )
        );
    }

    /**
     * @param Album   $album
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/update-position", name="admin_work_album_photos_update_positions")
     * @Method("POST")
     */
    public function updatePositionAjaxAction(Request $request, Album $album)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedException('This path is only accessible in ajax');
        }

        $idWithPositionList = $request->get('idWithPositionList');
        foreach ($idWithPositionList as $idWithPosition) {
            /** @var Photo $photo */
            $photo = $this->getPhotoRepository()->find($idWithPosition['id']);
            if (!$photo || $photo->getAlbum() != $album) {
                throw new NotFoundHttpException();
            }
            $photo->setPosition($idWithPosition['position']);
        }

        $this->getEntityManager()->flush();

        return new JsonResponse(
            array(
                'status'  => 'success',
                'message' => $this->getTranslator()->trans('form.updatePosition.message', array(), 'adminWorkPhoto')
            )
        );
    }

    /**
     * @param Photo $photo
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="admin_work_album_photo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Photo $photo)
    {
        $this->getEntityManager()->remove($photo);
        $this->getEntityManager()->flush();

        $message = $this->getSuccessMessageFromContext('delete');
        $this->addFlashMessage($message, 'success');

        return $this->redirect($this->generateUrl('admin_work_albums'));
    }

    /**
     * @param $context
     *
     * @return string
     */
    private function getSuccessMessageFromContext($context = 'create')
    {
        return $this->getTranslator()->trans('form.' . $context . '.success.message', array(), 'adminWorkPhoto');
    }

}