<?php

namespace Infrastructure\Persistence\Redis;

require_once __DIR__ . '/../PersistentPostRepositoryTest.php';

use Predis\Client;

class RedisPostRepositoryTest extends \PersistentPostRepositoryTest
{
    protected function createPostRepository()
    {
        $client = new Client();
        $client->flushall();

        return new RedisPostRepository($client);
    }
}
