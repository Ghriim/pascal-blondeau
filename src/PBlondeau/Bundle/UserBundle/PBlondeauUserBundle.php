<?php

namespace PBlondeau\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PBlondeauUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
