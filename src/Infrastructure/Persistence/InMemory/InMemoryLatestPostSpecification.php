<?php

namespace Infrastructure\Persistence\InMemory;

use Domain\Model\Post;

class InMemoryLatestPostSpecification implements InMemoryPostSpecification
{
    private $since;

    public function __construct(\DateTime $since)
    {
        $this->since = $since;
    }

    public function specifies(Post $aPost)
    {
        return $aPost->createdAt() > $this->since;
    }
}
