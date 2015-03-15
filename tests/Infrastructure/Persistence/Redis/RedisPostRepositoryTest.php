<?php

namespace Infrastructure\Persistence\Redis;

require_once __DIR__ . '/../PostRepositoryTest.php';

use Predis\Client;

class RedisPostRepositoryTest extends \PostRepositoryTest
{
    protected function createPostRepository()
    {
        $client = new Client();
        $client->flushall();

        return new RedisPostRepository($client);
    }
}
