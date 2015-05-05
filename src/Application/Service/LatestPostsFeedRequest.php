<?php

namespace Application\Service;

class LatestPostsFeedRequest
{
    public $since;

    public function __construct(\DateTime $since)
    {
        $this->since = $since;
    }
}
