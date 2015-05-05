<?php

namespace Application\Service;

use Domain\Model\Body;
use Domain\Model\Post;
use Domain\Model\PostId;
use Infrastructure\Persistence\InMemory\InMemoryPostRepository;
use Infrastructure\Persistence\InMemory\InMemoryPostSpecificationFactory;

class LatestPostsFeedServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Infrastructure\Persistence\InMemory\InMemoryPostRepository
     */
    private $postRepository;

    /**
     * @var LatestPostsFeedService
     */
    private $latestPostsFeedService;

    public function setUp()
    {
        $this->latestPostsFeedService = new LatestPostsFeedService(
            $this->postRepository = new InMemoryPostRepository(),
            new InMemoryPostSpecificationFactory()
        );
    }

    /**
     * @test
     */
    public function shouldBuildAFeedFromLatestsPosts()
    {
        $this->addPost(1, 'first', '-2 hours');
        $this->addPost(2, 'second', '-3 hours');
        $this->addPost(3, 'third', '-5 hours');

        $feed = $this->latestPostsFeedService->execute(
            new LatestPostsFeedRequest(new \DateTime('-4 hours'))
        );

        $this->assertFeedContains([
            ['id' => 1, 'content' => 'first'],
            ['id' => 2, 'content' => 'second']
        ], $feed);
    }

    private function addPost($id, $content, $createdAt)
    {
        $this->postRepository->add(new Post(
            new PostId($id),
            new Body($content),
            new \DateTime($createdAt)
        ));
    }

    private function assertFeedContains($expected, $feed)
    {
        foreach ($expected as $index => $contents) {
            $this->assertArraySubset($contents, $feed[$index]);
            $this->assertNotNull($feed[$index]['created_at']);
        }
    }
}
