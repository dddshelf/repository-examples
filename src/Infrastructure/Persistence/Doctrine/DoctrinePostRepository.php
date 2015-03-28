<?php

namespace Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use Domain\Model\Post;
use Domain\Model\PostId;
use Domain\Model\CollectionPostRepository as PostRepository;

class DoctrinePostRepository implements PostRepository
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function add(Post $aPost)
    {
        $this->em->persist($aPost);
    }

    public function remove(Post $aPost)
    {
        $this->em->remove($aPost);
    }

    public function postOfId(PostId $anId)
    {
        return $this->em->find('Domain\Model\Post', $anId);
    }

    public function latestPosts(\DateTime $sinceADate)
    {
        return $this->em->createQueryBuilder()
            ->select('p')
            ->from('Domain\Model\Post', 'p')
            ->where('p.createdAt > :since')
            ->setParameter(':since', $sinceADate)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param DoctrinePostSpecification $specification
     *
     * @return Post[]
     */
    public function query($specification)
    {
        return $specification->buildQuery($this->em)->getResult();
    }

    public function nextIdentity()
    {
        return new PostId();
    }

    public function size()
    {
        return $this->em->createQueryBuilder()
            ->select('count(p.id)')
            ->from('Domain\Model\Post', 'p')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
