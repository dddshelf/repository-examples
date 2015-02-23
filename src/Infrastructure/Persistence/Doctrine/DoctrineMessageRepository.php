<?php

namespace Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use Domain\Model\Message;
use Domain\Model\MessageId;
use Domain\Model\MessageRepository;

class DoctrineMessageRepository implements MessageRepository
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function persist(Message $aMessage)
    {
        $this->em->persist($aMessage);
    }

    public function remove(Message $aMessage)
    {
        $this->em->remove($aMessage);
    }

    public function messageOfId(MessageId $anId)
    {
        return $this->em->find('Domain\Model\Message', $anId);
    }

    public function latestMessages(\DateTime $sinceADate)
    {
        return $this->em->createQueryBuilder()
            ->select('m')
            ->from('Domain\Model\Message', 'm')
            ->where('m.createdAt > :since')
            ->setParameter(':since', $sinceADate)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param DoctrineMessageSpecification $specification
     */
    public function query($specification)
    {
        return $specification->buildQuery($this->em)->getResult();
    }

    public function nextIdentity()
    {
        return new MessageId();
    }
}
