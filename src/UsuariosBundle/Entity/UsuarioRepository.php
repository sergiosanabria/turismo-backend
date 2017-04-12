<?php

namespace UsuariosBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of UserRepository
 *
 * @author Matias Solis de la Torre <matias.delatorre89@gmail.com>
 */
class UsuarioRepository extends EntityRepository {

    public function getAcUsers($filters) {
        $qb = $this->createQueryBuilder('u');

        if ($filters['username']) {

            $qb->andWhere('upper(u.username) LIKE upper(:username)')
                ->setParameter('username', '%' . $filters['username'] . '%');
        }

        return $qb->getQuery()->getResult();
    }

}
