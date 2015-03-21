<?php

namespace Infrastructure\Persistence\Sql;

use Domain\Model\Body;
use Domain\Model\Post;
use Domain\Model\PostId;
use Domain\Model\PersistentPostRepository as PostRepository;

class SqlPostRepository implements PostRepository
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Post $aPost)
    {
        ($this->exist($aPost)) ? $this->update($aPost) : $this->insert($aPost);
    }

    private function exist(Post $aPost)
    {
        $count = $this
            ->execute('SELECT COUNT(*) FROM posts WHERE id = :id', [
                ':id' => $aPost->id()->id()
            ])
            ->fetchColumn();

        return $count == 1;
    }

    private function insert(Post $aPost)
    {
        $sql = 'INSERT INTO posts (id, body, created_at) VALUES (:id, :body, :created_at)';

        $this->execute($sql, [
            'id' => $aPost->id()->id(),
            'body' => $aPost->body()->content(),
            'created_at' => $aPost->createdAt()->format(self::DATE_FORMAT)
        ]);
    }

    private function update(Post $aPost)
    {
        $sql = 'UPDATE posts SET body = :body WHERE id = :id';

        $this->execute($sql, [
            'id' => $aPost->id()->id(),
            'body' => $aPost->body()->content()
        ]);
    }

    private function execute($sql, array $parameters)
    {
        $st = $this->pdo->prepare($sql);

        $st->execute($parameters);

        return $st;
    }

    public function remove(Post $aPost)
    {
        $this->execute('DELETE FROM posts WHERE id = :id', [
            'id' => $aPost->id()->id()
        ]);
    }

    public function postOfId(PostId $anId)
    {
        $st = $this->execute('SELECT * FROM posts WHERE id = :id', [
            'id' => $anId->id()
        ]);

        if ($row = $st->fetch(\PDO::FETCH_ASSOC)) {
            return $this->buildPost($row);
        }

        return null;
    }

    private function buildPost($row)
    {
        return new Post(
            new PostId($row['id']),
            new Body($row['body']),
            new \DateTime($row['created_at'])
        );
    }

    public function latestPosts(\DateTime $sinceADate)
    {
        return $this->retrieveAll('SELECT * FROM posts WHERE created_at > :since_date', [
            'since_date' => $sinceADate->format(self::DATE_FORMAT)
        ]);
    }

    private function retrieveAll($sql, array $parameters = [])
    {
        $st = $this->pdo->prepare($sql);

        $st->execute($parameters);

        return array_map(function ($row) {
            return $this->buildPost($row);
        }, $st->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * @param SqlPostSpecification $specification
     *
     * @return Post[]
     */
    public function query($specification)
    {
        return $this->retrieveAll(
            'SELECT * FROM posts WHERE ' . $specification->toSqlClauses()
        );
    }

    public function nextIdentity()
    {
        return new PostId();
    }

    public function size()
    {
        return
            $this->pdo
                ->query('SELECT COUNT(*) FROM posts')
                ->fetchColumn();
    }

    public function initSchema()
    {
        $this->pdo->exec(<<<SQL
DROP TABLE IF EXISTS posts;

CREATE TABLE posts (
    id CHAR(36) PRIMARY KEY,
    body VARCHAR(250) NOT NULL,
    created_at DATETIME NOT NULL
)
SQL
        );
    }
}
