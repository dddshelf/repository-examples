<?php

namespace Infrastructure\Persistence;

use Domain\Model\Body;
use Domain\Model\Message;

abstract class MessageRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Domain\Model\MessageRepository
     */
    private $messageRepository;

    public function setUp()
    {
        $this->messageRepository = $this->createMessageRepository();
    }

    abstract protected function createMessageRepository();

    /**
     * @test
     */
    public function itShouldRemoveMessage()
    {
        $message = $this->persistMessage('irrelevant body');

        $this->messageRepository->remove($message);

        $this->assertMessageExist($message->id());
    }

    private function assertMessageExist($id)
    {
        $result = $this->messageRepository->messageOfId($id);
        $this->assertNull($result);
    }

    private function persistMessage($body, \DateTime $createdAt = null)
    {
        $this->messageRepository->persist(
            $message = new Message(
                $this->messageRepository->nextIdentity(),
                new Body($body),
                $createdAt
            )
        );

        return $message;
    }

    /**
     * @test
     */
    public function itShouldFetchLatestMessages()
    {
        $this->persistMessage('a year ago', new \DateTime('-1 year'));
        $this->persistMessage('a month ago', new \DateTime('-1 month'));
        $this->persistMessage('few hours ago', new \DateTime('-3 hours'));
        $this->persistMessage('few minutes ago', new \DateTime('-2 minutes'));

        $messages = $this->messageRepository->latestMessages(new \DateTime('-24 hours'));

        $this->assertCount(2, $messages);
        $this->assertEquals('few hours ago', $messages[0]->body()->content());
        $this->assertEquals('few minutes ago', $messages[1]->body()->content());
    }
}
