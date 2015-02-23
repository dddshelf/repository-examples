<?php

namespace Infrastructure\Persistence\Doctrine;

require_once __DIR__ . '/../MessageRepositoryTest.php';

use Infrastructure\Persistence\InMemory\InMemoryMessageRepository;
use Infrastructure\Persistence\MessageRepositoryTest;

class InMemoryMessageRepositoryTest extends MessageRepositoryTest
{
    protected function createMessageRepository()
    {
        return new InMemoryMessageRepository();
    }
}
