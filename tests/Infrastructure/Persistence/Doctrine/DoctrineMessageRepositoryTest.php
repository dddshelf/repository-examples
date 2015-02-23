<?php

namespace Infrastructure\Persistence\Doctrine;

require __DIR__ . '/../MessageRepositoryTest.php';

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools;
use Domain\Model\Message;
use Infrastructure\Persistence\MessageRepositoryTest;

class DoctrineMessageRepositoryTest extends MessageRepositoryTest
{
    protected function createMessageRepository()
    {
        $this->addCustomTypes();
        $em = $this->initEntityManager();
        $this->initSchema($em);

        return new PrecociousDoctrineMessageRepository($em);
    }

    private function addCustomTypes()
    {
        if (!Type::hasType('message_id')) {
            Type::addType('message_id', 'Infrastructure\Persistence\Doctrine\Types\MessageIdType');
        }

        if (!Type::hasType('body')) {
            Type::addType('body', 'Infrastructure\Persistence\Doctrine\Types\BodyType');
        }
    }

    protected function initEntityManager()
    {
        return EntityManager::create(
            ['url' => 'sqlite:///:memory:'],
            Tools\Setup::createXMLMetadataConfiguration(
                [__DIR__ . '/../../../../src/Infrastructure/Persistence/Doctrine/mapping'],
                $devMode = true
            )
        );
    }

    private function initSchema(EntityManager $em)
    {
        $tool = new Tools\SchemaTool($em);
        $tool->createSchema([
            $em->getClassMetadata('Domain\Model\Message')
        ]);
    }
}

class PrecociousDoctrineMessageRepository extends DoctrineMessageRepository
{
    public function persist(Message $aMessage)
    {
        parent::persist($aMessage);

        $this->em->flush();
    }

    public function remove(Message $aMessage)
    {
        parent::remove($aMessage);

        $this->em->flush();
    }
}
