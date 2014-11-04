<?php

namespace PBlondeau\Bundle\BiographyBundle\Controller\Biography;

use PBlondeau\Bundle\BiographyBundle\Entity\Biography;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PBlondeau\Bundle\CommonBundle\Controller\BaseController;

class PublicController extends BaseController
{
    /**
     * @Route("/biography", name="biography_public_display")
     * @Template()
     * @method("GET")
     */
    public function indexAction()
    {
        /** @var Biography $biography */
        $biography = $this->getBiographyRepository()->findForPublicDisplay();

        return array(
            'biography' => $biography
        );
    }
}
