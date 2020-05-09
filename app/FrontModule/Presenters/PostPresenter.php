<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Forms;
use Nette\Application\UI\Form;


class PostPresenter extends Nette\Application\UI\Presenter
{
    /** @persistent */
    public $backlink = '';
    
    /** @var Nette\Database\Context */
    private $database;

    /** @var Forms\CommentFormFactory */
	private $commentFactory;

    public function __construct(Forms\CommentFormFactory $commentFactory, Nette\Database\Context $database)
    {
        $this->database = $database;
        $this->commentFactory = $commentFactory;
    }

    public function actionCreate(): void
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }

    public function renderShow(int $postId): void
    {
        $post = $this->database->table('posts')->get($postId);
        if (!$post) {
            $this->error('Oops! Niečo sa pokazilo');
        }

        $this->template->post = $post;
        $this->template->comments = $post->related('comment')->order('created_at');
    }

    protected function createComponentCommentForm(): Form
    {
        return $this->commentFactory->create(function (): void {
			$this->restoreRequest($this->backlink);
			$this->redirect('Homepage:');
		});
    }

    public function commentFormSucceeded(Form $form, \stdClass $values): void
    {
        $postId = $this->getParameter('postId');

        $username = ($this->user->isLoggedIn()) ? $this->user->getIdentity()->username : $values->name;
        $useremail = ($this->user->isLoggedIn()) ? $this->user->getIdentity()->email : $values->email;

        $this->database->table('comments')->insert([
            'post_id' => $postId,
            'name' => $username,
            'email' => $useremail,
            'content' => $values->content,
        ]);

        $this->flashMessage('Děkuji za komentář', 'success');
        $this->redirect('this');
    }


}