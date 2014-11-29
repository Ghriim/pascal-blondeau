<?php

namespace PBlondeau\Bundle\WorkBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PBlondeau\Bundle\WorkBundle\Entity\Album;

class AlbumRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getQueryBuilder()
    {
        $qb = $this->createQueryBuilder('album');
        $qb->select('distinct album');

        return $qb;
    }

    /**
     * @param null $status
     *
     * @return Album[]
     */
    public function findForAdminList($status = null)
    {
        $qb = $this->getQueryBuilder();

        if ($status) {
            $qb->where('album.status = :status')
               ->setParameter('status', $status);
        }

        $qb->leftJoin('album.photos', 'photo');
        $qb->addSelect('photo');

        $qb->orderBy('album.status');
        $qb->addOrderBy('album.position');

        return $qb->getQuery()->getResult();
    }

}
