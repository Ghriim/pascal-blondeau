<?php

namespace PBlondeau\Bundle\WorkBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PBlondeau\Bundle\ExhibitionBundle\Entity\Exhibition;
use PBlondeau\Bundle\WorkBundle\Entity\Album;

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
     * @param null  $status
     *
     * @return Exhibition[]
     */
    public function findForAdminList(Album $album, $status = null)
    {
        $qb = $this->getQueryBuilder();

        $qb->where("photo.album = :album")
            ->setParameter("album", $album->getId());

        if ($status) {
            $qb->andWhere('photo.status = :status')
               ->setParameter('status', $status);
        }

        $qb->orderBy('photo.status');
        $qb->addOrderBy('photo.position');

        return $qb->getQuery()->getResult();
    }

}
