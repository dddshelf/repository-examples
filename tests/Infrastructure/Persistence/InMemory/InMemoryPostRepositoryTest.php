<?php

namespace Infrastructure\Persistence\Doctrine;

require_once __DIR__ . '/../CollectionPostRepositoryTest.php';

use Infrastructure\Persistence\InMemory\InMemoryPostRepository;

class InMemoryPostRepositoryTest extends \CollectionPostRepositoryTest
{
    protected function createPostRepository()
    {
        return new InMemoryPostRepository();
    }
}
