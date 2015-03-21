<?php

use Domain\Model\Body;
use Domain\Model\Post;
use Domain\Model\PostId;

abstract class PostRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Domain\Model\PostRepository
     */
    protected $postRepository;

    public function setUp()
    {
        $this->postRepository = $this->createPostRepository();
    }

    abstract protected function createPostRepository();

    abstract protected function persist(Post $post);

    /**
     * @test
     */
    public function itShouldRemovePost()
    {
        $post = $this->persistPost();

        $this->postRepository->remove($post);

        $this->assertPostExist($post->id());
    }

    protected function persistPost($body = 'irrelevant body', \DateTime $createdAt = null, PostId $id = null)
    {
        $this->persist(
            $post = new Post(
                $id ?: $this->postRepository->nextIdentity(),
                new Body($body),
                $createdAt
            )
        );

        return $post;
    }

    private function assertPostExist($id)
    {
        $result = $this->postRepository->postOfId($id);
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function itShouldFetchLatestPosts()
    {
        $this->persistPost('a year ago', new \DateTime('-1 year'));
        $this->persistPost('a month ago', new \DateTime('-1 month'));
        $this->persistPost('few hours ago', new \DateTime('-3 hours'));
        $this->persistPost('few minutes ago', new \DateTime('-2 minutes'));

        $posts = $this->postRepository->latestPosts(new \DateTime('-24 hours'));

        $this->assertCount(2, $posts);
        $this->assertEquals('few hours ago', $posts[0]->body()->content());
        $this->assertEquals('few minutes ago', $posts[1]->body()->content());
    }

    /**
     * @test
     */
    public function sizeShouldMatchNumberOfStoredPosts()
    {
        $this->persistPost();
        $this->persistPost();

        $size = $this->postRepository->size();

        $this->assertEquals(2, $size);
    }
}
