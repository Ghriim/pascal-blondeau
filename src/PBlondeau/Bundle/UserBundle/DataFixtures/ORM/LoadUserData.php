<?php

namespace PBlondeau\Bundle\UserBundle\DataFixtures\Orm;

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