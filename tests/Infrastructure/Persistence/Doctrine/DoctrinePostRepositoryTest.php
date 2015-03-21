<?php

namespace Infrastructure\Persistence\Doctrine;

require_once __DIR__ . '/../CollectionPostRepositoryTest.php';

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools;
use Domain\Model\Post;

class DoctrinePostRepositoryTest extends \CollectionPostRepositoryTest
{
    protected function createPostRepository()
    {
        $this->addCustomTypes();
        $em = $this->initEntityManager();
        $this->initSchema($em);

        return new PrecociousDoctrinePostRepository($em);
    }

    private function addCustomTypes()
    {
        if (!Type::hasType('post_id')) {
            Type::addType('post_id', 'Infrastructure\Persistence\Doctrine\Types\PostIdType');
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
                [__DIR__ . '/../../../../src/Infrastructure/Persistence/Doctrine/Mapping'],
                $devMode = true
            )
        );
    }

    private function initSchema(EntityManager $em)
    {
        $tool = new Tools\SchemaTool($em);
        $tool->createSchema([
            $em->getClassMetadata('Domain\Model\Post')
        ]);
    }
}

class PrecociousDoctrinePostRepository extends DoctrinePostRepository
{
    public function add(Post $aPost)
    {
        parent::add($aPost);

        $this->em->flush();
    }

    public function remove(Post $aPost)
    {
        parent::remove($aPost);

        $this->em->flush();
    }
}
