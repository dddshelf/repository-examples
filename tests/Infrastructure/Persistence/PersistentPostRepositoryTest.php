<?php

require_once __DIR__ . '/PostRepositoryTest.php';

use Domain\Model\Post;

/**
 * @property \Domain\Model\PersistentPostRepository $postRepository
 */
abstract class PersistentPostRepositoryTest extends PostRepositoryTest
{
    protected function persist(Post $post)
    {
        $this->postRepository->save($post);
    }

    /**
     * @test
     */
    public function itShouldReplacePersistedPost()
    {
        $post = $this->persistPost();
        $this->persistPost('new content', null, $post->id());

        $result = $this->postRepository->postOfId($post->id());

        $this->assertEquals('new content', $result->body()->content());
    }
}
