<?php

namespace Domain\Model;

interface MessageRepository
{
    public function persist(Message $aMessage);

    public function remove(Message $aMessage);

    /**
     * @return Message
     */
    public function messageOfId(MessageId $anId);

    /**
     * @return Message[]
     */
    public function latestMessages(\DateTime $sinceADate);

    /**
     * @param object $specification
     *
     * @return Message[]
     */
    public function query($specification);

    /**
     * @return MessageId
     */
    public function nextIdentity();
}
