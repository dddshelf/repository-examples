<?php

namespace Infrastructure\Persistence\Redis;

use Domain\Model\PostSpecificationFactory;

class RedisPostSpecificationFactory implements PostSpecificationFactory
{
    public function createLatestPosts(\DateTime $since)
    {
        return new RedisLatestPostSpecification($since);
    }
}
