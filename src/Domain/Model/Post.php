<?php

namespace Domain\Model;

class Post
{
    const EXPIRE_EDIT_TIME = 120;//seconds

    private $id;
    private $body;
    private $createdAt;

    public function __construct(
        PostId $anId,
        Body $aBody,
        \DateTime $createdAt = null
    ) {
        $this->id = $anId;
        $this->body = $aBody;
        $this->createdAt = $createdAt ?: new \DateTime();
    }

    public function editBody(Body $aNewBody)
    {
        if ($this->editExpired()) {
            throw new \RuntimeException('Edit time expired');
        }

        $this->body = $aNewBody;
    }

    private function editExpired()
    {
        $expiringTime = $this->createdAt->getTimestamp() + self::EXPIRE_EDIT_TIME;

        return $expiringTime < time();
    }

    public function id()
    {
        return $this->id;
    }

    public function body()
    {
        return $this->body;
    }

    public function createdAt()
    {
        return $this->createdAt;
    }
}
