<?php

namespace Infrastructure\Persistence\Sql;

use Domain\Model\PostSpecificationFactory;

class SqlPostSpecificationFactory implements PostSpecificationFactory
{
    public function createLatestPosts(\DateTime $since)
    {
        return new SqlLatestPostSpecification($since);
    }
}
