<?php

namespace Domain\Model;

interface PersistentPostRepository extends PostRepository
{
    public function save(Post $aPost);
}
