<?php

namespace Domain\Model;

class Body
{
    const MIN_LENGTH = 3;
    const MAX_LENGTH = 250;

    private $content;

    public function __construct($content)
    {
        $this->setContent(trim($content));
    }

    private function setContent($content)
    {
        $this->assertNotEmpty($content);
        $this->assertFitsLength($content);

        $this->content = $content;
    }

    private function assertNotEmpty($content)
    {
        if (empty($content)) {
            throw new \DomainException('Empty body');
        }
    }

    private function assertFitsLength($content)
    {
        if (strlen($content) < self::MIN_LENGTH) {
            throw new \DomainException('Body is too sort');
        }

        if (strlen($content) > self::MAX_LENGTH) {
            throw new \DomainException('Body is too long');
        }
    }

    public function content()
    {
        return $this->content;
    }
}
