<?php

namespace PBlondeau\Bundle\ContactBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;
use PBlondeau\Bundle\ContactBundle\Form\Type\ContactType;
use PBlondeau\Bundle\ContactBundle\Form\Model\ContactEmail;

class ContactController extends BaseController
{
    /**
     * @param Request $request
     *
     * @return array
     *
     * @Route("/contact", name="contact_public_display")
     * @Template()
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $contactEmail = new ContactEmail();

        $form = $this->createForm(
            new ContactType(),
            $contactEmail,
            array(
                'action' => $this->generateUrl('contact_public_display'),
                'method' => 'POST',
                'attr'   => array(
                    'class'      => 'form form-horizontal',
                ),
            )
        );

        if ($request->isMethod("POST")) {
            $form->submit($request);

            if ($form->isValid()) {
                $message = \Swift_Message::newInstance();
                $message->setSubject($contactEmail->getSubject())
                        ->setFrom($contactEmail->getSender())
                        ->setTo($this->container->getParameter('mailer_recipient'))
                        ->setBody($contactEmail->getContent());

                $acceptedRecipients = $this->get('mailer')->send($message);
                $this->setMailingFeedbackFlashMessage($acceptedRecipients);

                return $this->redirect($this->generateUrl('contact_public_display'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param int $acceptedRecipients
     */
    private function setMailingFeedbackFlashMessage($acceptedRecipients)
    {
        if ($acceptedRecipients) {
            $message = $this->getTranslator()->trans('form.success.message', array(), 'contact');
            $this->addFlashMessage($message, 'success');
        } else {
            $message = $this->getTranslator()->trans(
                'form.error.message',
                array('%mailer_recipient%' => $this->container->getParameter('mailer_recipient')),
                'contact'
            );
            $this->addFlashMessage($message, 'warning');
        }
    }
}
