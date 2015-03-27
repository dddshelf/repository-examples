<?php

namespace Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;

class DoctrineLatestPostSpecification implements DoctrinePostSpecification
{
    private $since;

    public function __construct(\DateTime $since)
    {
        $this->since = $since;
    }

    public function buildQuery(EntityManager $em)
    {
        return $em->createQueryBuilder()
            ->select('p')
            ->from('Domain\Model\Post', 'p')
            ->where('p.createdAt > :since')
            ->setParameter(':since', $this->since)
            ->getQuery();
    }
}
