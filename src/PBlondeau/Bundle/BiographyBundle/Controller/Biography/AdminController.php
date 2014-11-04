<?php

namespace PBlondeau\Bundle\BiographyBundle\Controller\Biography;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;
use PBlondeau\Bundle\BiographyBundle\Entity\Biography;
use PBlondeau\Bundle\BiographyBundle\Form\Type\BiographyType;

/**
 * Biography Admin controller.
 *
 * @Route("/admin/biography")
 */
class AdminController extends BaseController
{

    /**
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/save", name="admin_biography_save")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function saveAction(Request $request)
    {
        /** @var Biography $biography */
        $biography = $this->getBiographyRepository()->findForSaveAdmin();

        if (!$biography) {
            $biography = new Biography();
        }

        $form = $this->buildSaveForm($biography);

        if ($request->isMethod("POST")) {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getEntityManager()->persist($biography);
                $this->getEntityManager()->flush();

                $message = $this->getTranslator()->trans('form.save.success.message', array(), 'adminBiography');
                $this->addFlashMessage($message, 'success');

                return $this->redirect($this->generateUrl("admin_biography_save"));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Biography $biography
     *
     * @return \Symfony\Component\Form\Form
     */
    private function buildSaveForm(Biography $biography)
    {
        return $this->createForm(
            new BiographyType(),
            $biography,
            array(
                'action' => $this->generateUrl('admin_biography_save'),
                'method' => 'POST',
                'attr'   => array(
                    'class'      => 'form form-horizontal',
                    'novalidate' => 'novalidate'
                ),
            )
        );
    }
}
