<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use Nette;
use App\Model\ArticleManager;
use Nette\Application\UI\Presenter;


final class BasePresenter extends Presenter
{

    /** @var ArticleManager */
    private $articleManager;

    public function __construct(ArticleManager $articleManager) 
    {
        $this->articleManager = $articleManager;
    }

    public function renderDefault( int $page = 1 ): void
    {
        $articlesCount = $this->articleManager->getPublishedArticlesCount();

        $paginator = new Nette\Utils\Paginator;
		$paginator->setItemCount($articlesCount);   // celkový počet článků
		$paginator->setItemsPerPage(6);             // počet položek na stránce
        $paginator->setPage($page);                 // číslo aktuální stránky
        
        $articles = $this->articleManager->getPublicArticles( $paginator->getLength(), $paginator->getOffset() );
        
        // kterou předáme do šablony
		$this->template->posts = $articles;
		// a také samotný Paginator pro zobrazení možností stránkování
		$this->template->paginator = $paginator;
        // $this->template->posts = $this->articleManager->getPublicArticles()->limit(6);
    }

}
