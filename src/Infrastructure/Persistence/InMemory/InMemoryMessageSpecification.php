<?php

namespace Infrastructure\Persistence\InMemory;

use Domain\Model\Message;

interface InMemoryMessageSpecification
{
    /**
     * @return boolean
     */
    public function specifies(Message $aMessage);
}
