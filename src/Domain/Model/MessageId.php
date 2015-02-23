<?php

namespace Domain\Model;

use Rhumsaa\Uuid\Uuid;

class MessageId
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

    public function equals(MessageId $anId)
    {
        return $this->id === $anId->id();
    }

    public function __toString()
    {
        return $this->id;
    }
}
