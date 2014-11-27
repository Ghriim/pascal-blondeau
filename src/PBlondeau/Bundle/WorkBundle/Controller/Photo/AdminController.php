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
     * @Route("/{id}", name="admin_work_photos")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, Album $album)
    {
        $photos = $this->getPaginator()->paginate(
            $this->getPhotoRepository()->findForAdminList($album),
            $this->get('request')->query->get('page', 1)
        );

        $addPhotoForm = $this->buildAddForm($album);
        if($request->isMethod("POST")) {
            $addPhotoForm->submit($request);

            if ($addPhotoForm->isValid()) {

            }
        }

        return $this->render(
            'PBlondeauWorkBundle:Photo/Admin:index.html.twig',
            array(
                'album'        => $album,
                'photos'       => $photos,
                'addPhotoForm' => $addPhotoForm->createView(),
            )
        );
    }

    /**
     * @param Photo $photo
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}", name="admin_work_album_photo_delete_ajax")
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
                'attr'   => array('class' => 'form form-horizontal'),
            )
        );
    }

}
