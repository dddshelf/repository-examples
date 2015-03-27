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

    abstract protected function persist(Post $post);

    private function assertPostExist($id)
    {
        $result = $this->postRepository->postOfId($id);
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function itShouldFetchLatestPostsByMethod()
    {
        $this->assertPostsAreFetchedWith(function() {
            return $this->postRepository->latestPosts(new \DateTime('-24 hours'));
        });
    }

    private function assertPostsAreFetchedWith(callable $fetchPostsFn) {
        $this->persistPost('a year ago', new \DateTime('-1 year'));
        $this->persistPost('a month ago', new \DateTime('-1 month'));
        $this->persistPost('few hours ago', new \DateTime('-3 hours'));
        $this->persistPost('few minutes ago', new \DateTime('-2 minutes'));

        $this->assertPostContentsEquals(['few hours ago', 'few minutes ago'], $fetchPostsFn());
    }

    private function assertPostContentsEquals($expectedContents, array $posts)
    {
        $postContents = array_map(function(Post $post) {
            return $post->body()->content();
        }, $posts);

        $this->assertEquals(
            array_diff($expectedContents, $postContents),
            array_diff($postContents, $expectedContents)
        );
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

    /**
     * @test
     */
    public function itShouldFetchLatestPostsBySpecification()
    {
        $this->assertPostsAreFetchedWith(function() {
            return $this->postRepository->query(
                $this->createLatestPostSpecification(new \DateTime('-24 hours'))
            );
        });
    }

    abstract protected function createLatestPostSpecification(\DateTime $since);
}
