<?php

namespace Infrastructure\Persistence\Redis;

use Domain\Model\Post;
use Domain\Model\PostId;
use Domain\Model\PersistentPostRepository as PostRepository;
use Predis\Client;

class RedisPostRepository implements PostRepository
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function save(Post $aPost)
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
        return $this->filterPosts(
            function(Post $post) use ($sinceADate) {
                return $post->createdAt() > $sinceADate;
            }
        );
    }

    private function filterPosts(callable $fn)
    {
        return array_values(array_filter(array_map(function($data) {
            return unserialize($data);
        }, $this->client->hgetall('posts')), $fn));
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
