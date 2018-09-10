<?php

namespace AppBundle\Repository;

/**
 * PlanRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanRepository extends \Doctrine\ORM\EntityRepository
{
	public function findPreviousAndCurrentPlans($now, $month_ago, $choir)
    {
        return $this->createQueryBuilder('s')
		    ->select('s')
		    ->where('s.commence >= :month_ago')
		    ->andWhere('s.commence <= :now')
		    ->andWhere('s.choir = :choir')
		    ->setParameter('month_ago', $month_ago)
		    ->setParameter('now', $now)
		    ->setParameter('choir', $choir)
		    ->orderBy('s.commence', 'ASC')
		    ->getQuery()
		    ->getResult();
    }
    
	public function findPreviousAndCurrentPlansWithRange($start, $end, $choir)
    {
        return $this->createQueryBuilder('s')
		    ->select('s')
		    ->where('s.commence >= :start')
		    ->andWhere('s.commence <= :end')
		    ->andWhere('s.choir = :choir')
		    ->setParameter('start', $start)
		    ->setParameter('end', $end)
		    ->setParameter('choir', $choir)
		    ->orderBy('s.commence', 'ASC')
		    ->getQuery()
		    ->getResult();
    }
}