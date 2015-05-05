<?php

namespace Domain\Model;

interface PostSpecificationFactory
{
    public function createLatestPosts(\DateTime $since);
}
