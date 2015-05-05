<?php

namespace Infrastructure\Persistence\InMemory;

use Domain\Model\PostSpecificationFactory;

class InMemoryPostSpecificationFactory implements PostSpecificationFactory
{
    public function createLatestPosts(\DateTime $since)
    {
        return new InMemoryLatestPostSpecification($since);
    }
}
