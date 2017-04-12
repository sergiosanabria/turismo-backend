<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AtraccionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AtraccionRepository extends EntityRepository
{
    private $itemsPorPagina = 10;

//    public function get($page)
//    {
//        $hoy = new \DateTime();
//        $qb = $this->createQueryBuilder('a')
//            ->andWhere('a.activo = true ')
//            ->andWhere(':hoy BETWEEN a.visibleDesde AND a.visibleHasta')
//            ->setParameter('hoy', $hoy )
//            ->setMaxResults($this->itemsPorPagina)
//            ->setFirstResult(($page - 1) * $this->itemsPorPagina)
//            ->orderBy('a.orden', 'ASC')
//            ->orderBy('a.creado', 'ASC')
//        ;
//
//
//        return $qb->getQuery()->getResult();
//    }

    public function get()
    {
        $hoy = new \DateTime();
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.activo = true ')
            ->orderBy('a.orden', 'ASC')
            ->orderBy('a.creado', 'ASC')
        ;


        return $qb->getQuery()->getResult();
    }
}
