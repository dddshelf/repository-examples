<?php

namespace Domain\Model;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function bodyShouldBeEditable()
    {
        $message = $this->createMessage('old content');

        $message->editBody(new Body('new content'));

        $this->assertEquals('new content', $message->body()->content());
    }

    private function createMessage($content, $createdAt = null)
    {
        return new Message(new MessageId(), new Body($content), $createdAt);
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function editExpiredMessageShouldThrowException()
    {
        $message = $this->createMessage('content', new \DateTime('-1 year'));

        $message->editBody(new Body('new content'));
    }
}
