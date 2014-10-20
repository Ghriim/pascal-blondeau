<?php

namespace PBlondeau\Bundle\NewsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PBlondeau\Bundle\NewsBundle\Entity\News;

class NewsRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getQueryBuilder()
    {
        $qb = $this->createQueryBuilder('news');
        $qb->select('distinct news');

        return $qb;
    }

    /**
     * @param null $status
     *
     * @return News[]
     */
    public function findForAdminList($status = null)
    {
        $qb = $this->getQueryBuilder();

        if ($status) {
            $qb->where('news.status = :status')
               ->setParameter('status', $status);
        }

        $qb->orderBy('news.status');

        return $qb->getQuery()->getResult();
    }

}
