<?php

namespace Domain\Model;

class PostTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function bodyShouldBeEditable()
    {
        $post = $this->createPost('old content');

        $post->editBody(new Body('new content'));

        $this->assertEquals('new content', $post->body()->content());
    }

    private function createPost($content, $createdAt = null)
    {
        return new Post(new PostId(), new Body($content), $createdAt);
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function editExpiredPostShouldThrowException()
    {
        $post = $this->createPost('content', new \DateTime('-1 year'));

        $post->editBody(new Body('new content'));
    }
}
