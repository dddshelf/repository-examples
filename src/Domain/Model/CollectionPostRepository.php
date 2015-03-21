<?php

namespace Domain\Model;

interface CollectionPostRepository extends PostRepository
{
    public function add(Post $aPost);
}
