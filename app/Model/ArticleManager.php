<?php 

namespace App\Model;

use Nette;

class ArticleManager
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function getPublicArticles( int $limit = 6, int $offset = 0 )
    {
        return $this->database->table('posts')
            ->where('created_at < ', new \DateTime)
            ->limit($limit, $offset)
            ->order('created_at DESC');
    }

    public function getPublishedArticlesCount()
    {
        return count( $this->getPublicArticles() );
    }
}