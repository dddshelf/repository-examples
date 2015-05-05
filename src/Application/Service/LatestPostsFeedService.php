<?php

namespace Application\Service;

use Domain\Model\Post;
use Domain\Model\PostRepository;
use Domain\Model\PostSpecificationFactory;

class LatestPostsFeedService
{
    private $postRepository;
    private $postSpecificationFactory;

    public function __construct(
        PostRepository $postRepository,
        PostSpecificationFactory $postSpecificationFactory
    ) {
        $this->postRepository = $postRepository;
        $this->postSpecificationFactory = $postSpecificationFactory;
    }

    /**
     * @param LatestPostsFeedRequest $request
     */
    public function execute($request)
    {
        $posts = $this->postRepository->query(
            $this->postSpecificationFactory->createLatestPosts($request->since)
        );

        return array_map(function(Post $post) {
            return [
                'id' => $post->id()->id(),
                'content' => $post->body()->content(),
                'created_at' => $post->createdAt()
            ];
        }, $posts);
    }
}
