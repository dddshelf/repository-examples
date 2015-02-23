<?php

namespace Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;

interface DoctrineMessageSpecification
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function buildQuery(EntityManager $em);
}
