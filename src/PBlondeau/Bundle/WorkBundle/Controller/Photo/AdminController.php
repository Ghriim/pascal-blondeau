<?php

namespace PBlondeau\Bundle\WorkBundle\Controller\Photo;

use PBlondeau\Bundle\WorkBundle\Entity\Album;
use PBlondeau\Bundle\WorkBundle\Form\Type\PhotoType;
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
     * @param Request $request
     * @param Album $album
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{id}/photos", name="admin_work_photos")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, Album $album)
    {
        $photos = $this->getPaginator()->paginate(
            $this->getPhotoRepository()->findForAdminList($album),
            $this->get('request')->query->get('page', 1)
        );

        $addPhotoForm = $this->buildAddForm($album);
        if ($request->isMethod("POST")) {
            $addPhotoForm->submit($request);

            if ($addPhotoForm->isValid()) {
                $files = $addPhotoForm->get("files")->getData();
                foreach ($files as $file) {
                    $photo = new Photo();
                    $photo->setUser($this->getUser());
                    $photo->setAlbum($album);
                    $photo->file = $file;

                    $this->getEntityManager()->persist($photo);
                }

                $this->getEntityManager()->flush();

                $message = $this->getSuccessMessageFromContext();
                $this->addFlashMessage($message, 'success');

                return $this->redirect(
                    $this->generateUrl('admin_work_photos', array("id" => $album->getId()))
                );
            }
        }

        return $this->render(
            'PBlondeauWorkBundle:Photo/Admin:index.html.twig',
            array(
                'album' => $album,
                'photos' => $photos,
                'addPhotoForm' => $addPhotoForm->createView(),
            )
        );
    }

    /**
     * @param Request $request
     * @param Album   $album
     *
     * @return JsonResponse
     *
     * @Route("/{id}/photos/update-position", name="admin_work_photos_update_positions")
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
            $photo = $this->getPhotoRepository()->findOneBy(
                array(
                    "id" => $idWithPosition['id'],
                    "album" => $album
                )
            );

            if (!$photo) {
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
     * @param Album $album
     * @param integer $photoId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}/photos/{photoId}", name="admin_work_photo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Album $album, $photoId)
    {
        $photo = $this->getPhotoRepository()->find($photoId);
        if(is_null($photo)) {
            throw new NotFoundHttpException();
        }

        $this->getEntityManager()->remove($photo);
        $this->getEntityManager()->flush();

        $message = $this->getSuccessMessageFromContext('delete');
        $this->addFlashMessage($message, 'success');

        return $this->redirect(
            $this->generateUrl('admin_work_photos', array("id" => $album->getId()))
        );
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

    /**
     * @param Album $album
     *
     * @return \Symfony\Component\Form\Form
     */
    private function buildAddForm(Album $album)
    {
        return $this->createForm(
            new PhotoType(),
            null,
            array(
                'action' => $this->generateUrl('admin_work_photos', array('id' => $album->getId())),
                'method' => 'POST',
                'attr' => array('class' => 'form form-horizontal'),
            )
        );
    }

}
