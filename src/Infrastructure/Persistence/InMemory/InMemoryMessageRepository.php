<?php

namespace Infrastructure\Persistence\InMemory;

use Domain\Model\Message;
use Domain\Model\MessageId;
use Domain\Model\MessageRepository;

class InMemoryMessageRepository implements MessageRepository
{
    private $messages = [];

    public function persist(Message $aMessage)
    {
        $this->messages[$aMessage->id()->id()] = $aMessage;
    }

    public function remove(Message $aMessage)
    {
        unset($this->messages[$aMessage->id()->id()]);
    }

    public function messageOfId(MessageId $anId)
    {
        if (isset($this->messages[$anId->id()])) {
            return $this->messages[$anId->id()];
        }

        return null;
    }

    public function latestMessages(\DateTime $sinceADate)
    {
        return $this->filterMessages(
            function(Message $message) use ($sinceADate) {
                return $message->createdAt() > $sinceADate;
            }
        );
    }

    private function filterMessages(callable $fn)
    {
        return array_values(array_filter($this->messages, $fn));
    }

    /**
     * @param InMemoryMessageSpecification $specification
     */
    public function query($specification)
    {
        return $this->filterMessages(
            function(Message $message) use ($specification) {
                return $specification->specifies($message);
            }
        );
    }

    public function nextIdentity()
    {
        return new MessageId();
    }
}
