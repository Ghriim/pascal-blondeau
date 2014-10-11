<?php

namespace PBlondeau\Bundle\PressBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PBlondeau\Bundle\PressBundle\Entity\PressArticle;

class PressArticleRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getQueryBuilder()
    {
        $qb = $this->createQueryBuilder('pressArticle');
        $qb->select('distinct pressArticle');

        return $qb;
    }

    /**
     * @param null $status
     *
     * @return PressArticle[]
     */
    public function findForAdminList($status = null)
    {
        $qb = $this->getQueryBuilder();

        if ($status) {
            $qb->where('pressArticle.status = :status')
               ->setParameter('status', $status);
        }

        $qb->orderBy('pressArticle.status');
        $qb->addOrderBy('pressArticle.position');

        return $qb->getQuery()->getResult();
    }

}
