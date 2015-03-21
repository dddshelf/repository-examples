<?php

require_once __DIR__ . '/PostRepositoryTest.php';

use Domain\Model\Body;
use Domain\Model\Post;

/**
 * @property \Domain\Model\CollectionPostRepository $postRepository
 */
abstract class CollectionPostRepositoryTest extends PostRepositoryTest
{
    protected function persist(Post $post)
    {
        $this->postRepository->add($post);
    }

    /**
     * @test
     */
    public function entityChangesShouldBePersistedAutomatically()
    {
        $post = $this->persistPost();
        $post->editBody(new Body('new content'));

        $result = $this->postRepository->postOfId($post->id());

        $this->assertEquals('new content', $result->body()->content());
    }
}
