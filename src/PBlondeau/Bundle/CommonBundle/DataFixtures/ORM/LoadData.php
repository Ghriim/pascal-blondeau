<?php

namespace PBlondeau\Bundle\CommonBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class LoadData extends DataFixtureLoader
{
    public function getFixtures()
    {
        return  array(
            __DIR__ . '/../../../UserBundle/DataFixtures/ORM/users.yml',
            __DIR__ . '/../../../ExhibitionBundle/DataFixtures/ORM/exhibitions.yml',
            __DIR__ . '/../../../NewsBundle/DataFixtures/ORM/news.yml',
            __DIR__ . '/../../../PressBundle/DataFixtures/ORM/pressArticles.yml',
            __DIR__ . '/../../../WorkBundle/DataFixtures/ORM/albums.yml',
            //__DIR__ . '/../../../WorkBundle/DataFixtures/ORM/photos.yml'
        );
    }
}