<?php

namespace PBlondeau\Bundle\ExhibitionBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PBlondeau\Bundle\ExhibitionBundle\Entity\Exhibition;

class ExhibitionRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getQueryBuilder()
    {
        $qb = $this->createQueryBuilder('exhibition');
        $qb->select('distinct exhibition');

        return $qb;
    }

    /**
     * @param null $status
     *
     * @return Exhibition[]
     */
    public function findForAdminList($status = null)
    {
        $qb = $this->getQueryBuilder();

        if ($status) {
            $qb->where('exhibition.status = :status')
               ->setParameter('status', $status);
        }

        $qb->orderBy('exhibition.status');
        $qb->addOrderBy('exhibition.position');

        return $qb->getQuery()->getResult();
    }

}
