<?php

namespace Domain\Model;

interface PostRepository
{
    public function persist(Post $aPost);

    public function remove(Post $aPost);

    /**
     * @return Post
     */
    public function postOfId(PostId $anId);

    /**
     * @return Post[]
     */
    public function latestPosts(\DateTime $sinceADate);

    /**
     * @param object $specification
     *
     * @return Post[]
     */
    public function query($specification);

    /**
     * @return PostId
     */
    public function nextIdentity();

    /**
     * @return integer
     */
    public function size();
}
