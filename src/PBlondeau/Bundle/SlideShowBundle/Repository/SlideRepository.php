<?php

namespace PBlondeau\Bundle\SlideShowBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PBlondeau\Bundle\SlideShowBundle\Entity\Slide;

class SlideRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getQueryBuilder()
    {
        $qb = $this->createQueryBuilder('slide');
        $qb->select('distinct slide');

        return $qb;
    }

    /**
     * @param null $status
     *
     * @return Slide[]
     */
    public function findForAdminList($status = null)
    {
        $qb = $this->getQueryBuilder();

        if ($status) {
            $qb->where('slide.status = :status')
               ->setParameter('status', $status);
        }

        $qb->orderBy('slide.status');
        $qb->addOrderBy('slide.position');

        return $qb->getQuery()->getResult();
    }

}
