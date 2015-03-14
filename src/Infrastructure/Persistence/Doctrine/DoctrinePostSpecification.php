<?php

namespace Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;

interface DoctrinePostSpecification
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function buildQuery(EntityManager $em);
}
