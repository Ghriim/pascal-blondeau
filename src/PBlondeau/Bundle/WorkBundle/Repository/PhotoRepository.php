<?php

namespace PBlondeau\Bundle\WorkBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PBlondeau\Bundle\WorkBundle\Entity\Album;
use PBlondeau\Bundle\WorkBundle\Entity\Photo;

class PhotoRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getQueryBuilder()
    {
        $qb = $this->createQueryBuilder('photo');
        $qb->select('distinct photo');

        return $qb;
    }

    /**
     * @param Album $album
     *
     * @return Photo[]
     */
    public function findForAdminList(Album $album)
    {
        $qb = $this->getQueryBuilder();

        $qb->where("photo.album = :album")
            ->setParameter("album", $album->getId());

        $qb->orderBy('photo.position');

        return $qb->getQuery()->getResult();
    }

}
