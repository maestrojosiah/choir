<?php

namespace AppBundle\Repository;

/**
 * RehearsalRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RehearsalRepository extends \Doctrine\ORM\EntityRepository
{
	public function findOneFutureRehearsal($date, $choir)
    {
        return $this->createQueryBuilder('s')
		    ->select('s')
		    // ->where('s.deleted = 0 AND s.transaction != :sus')
		    ->where('s.day >= :date')
		    ->andWhere('s.choir = :choir')
		    ->setParameter('date', $date)
		    ->setParameter('choir', $choir)
		    // ->setParameter('to', $to)
		    ->orderBy('s.day', 'ASC')
		    // ->getResult();
		    ->setMaxResults(1)
		    ->getQuery()
		    ->getOneOrNullResult();
    }

	public function findPreviousAndCurrentRehearsals($now, $month_ago, $choir)
    {
        return $this->createQueryBuilder('s')
		    ->select('s')
		    ->where('s.day >= :month_ago')
		    ->andWhere('s.day <= :now')
		    ->andWhere('s.choir = :choir')
		    ->setParameter('month_ago', $month_ago)
		    ->setParameter('now', $now)
		    ->setParameter('choir', $choir)
		    ->orderBy('s.day', 'ASC')
		    ->getQuery()
		    ->getResult();
    }
    
	public function findPreviousAndCurrentRehearsalsWithRange($start, $end, $choir)
    {
        return $this->createQueryBuilder('s')
		    ->select('s')
		    ->where('s.day >= :start')
		    ->andWhere('s.day <= :end')
		    ->andWhere('s.choir = :choir')
		    ->setParameter('start', $start)
		    ->setParameter('end', $end)
		    ->setParameter('choir', $choir)
		    ->orderBy('s.day', 'ASC')
		    ->getQuery()
		    ->getResult();
    }
}
