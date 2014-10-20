<?php

namespace PBlondeau\Bundle\UserBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class LoadUserData extends DataFixtureLoader
{
    public function getFixtures()
    {
        return  array(
            __DIR__ . '/users.yml',

        );
    }
}
