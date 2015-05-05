<?php

namespace Infrastructure\Persistence\Doctrine;

use Domain\Model\PostSpecificationFactory;

class DoctrinePostSpecificationFactory implements PostSpecificationFactory
{
    public function createLatestPosts(\DateTime $since)
    {
        return new DoctrineLatestPostSpecification($since);
    }
}
