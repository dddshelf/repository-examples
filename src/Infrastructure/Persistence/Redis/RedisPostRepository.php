<?php

namespace Infrastructure\Persistence\Redis;

use Domain\Model\Post;
use Domain\Model\PostId;
use Domain\Model\PostRepository;
use Predis\Client;

class RedisPostRepository implements PostRepository
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function persist(Post $aPost)
    {
        $this->client->hset('posts', (string) $aPost->id(), serialize($aPost));
    }

    public function remove(Post $aPost)
    {
        $this->client->hdel('posts', (string) $aPost->id());
    }

    public function postOfId(PostId $anId)
    {
        if ($data = $this->client->hget('posts', (string) $anId)) {
            return unserialize($data);
        }

        return null;
    }

    public function latestPosts(\DateTime $sinceADate)
    {
        $latest = $this->filterPosts(
            function(Post $post) use ($sinceADate) {
                return $post->createdAt() > $sinceADate;
            }
        );

        $this->sortByCreatedAt($latest);

        return array_values($latest);
    }

    private function filterPosts(callable $fn)
    {
        return array_filter(array_map(function($data) {
            return unserialize($data);
        }, $this->client->hgetall('posts')), $fn);
    }

    private function sortByCreatedAt(&$posts)
    {
        usort($posts, function(Post $a, Post $b) {
            if ($a->createdAt() == $b->createdAt()) {
                return 0;
            }
            return ($a->createdAt() < $b->createdAt()) ? -1 : 1;
        });
    }

    /**
     * @param RedisPostSpecification $specification
     *
     * @return Post[]
     */
    public function query($specification)
    {
        return $this->filterPosts(
            function(Post $post) use ($specification) {
                return $specification->specifies($post);
            }
        );
    }

    public function nextIdentity()
    {
        return new PostId();
    }

    public function size()
    {
        return $this->client->hlen('posts');
    }
}
