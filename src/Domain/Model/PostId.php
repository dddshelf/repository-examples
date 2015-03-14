<?php

namespace Domain\Model;

use Rhumsaa\Uuid\Uuid;

class PostId
{
    private $id;

    public function __construct($id = null)
    {
        $this->id = $id ?: Uuid::uuid4()->toString();
    }

    public function id()
    {
        return $this->id;
    }

    public function equals(PostId $anId)
    {
        return $this->id === $anId->id();
    }

    public function __toString()
    {
        return $this->id;
    }
}
