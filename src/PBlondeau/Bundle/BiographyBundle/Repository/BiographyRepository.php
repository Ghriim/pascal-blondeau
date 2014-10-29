<?php

namespace PBlondeau\Bundle\BiographyBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PBlondeau\Bundle\BiographyBundle\Entity\Biography;

class BiographyRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getQueryBuilder()
    {
        $qb = $this->createQueryBuilder('biography');
        $qb->select('distinct biography');

        return $qb;
    }

    /**
     * @return Biography
     */
    public function findForSaveAdmin()
    {
        $qb = $this->getQueryBuilder();
        return $qb->getQuery()->getFirstResult();
    }
} 