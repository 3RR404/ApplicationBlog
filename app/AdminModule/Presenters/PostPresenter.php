<?php

namespace App\AdminModule\Presenters;

use Nette;
use Nette\Application\UI\Form;
use \Nette\Application\UI\Presenter;

final class PostPresenter extends BasePresenter
{
    /** @var Nette\Database\Context */
    private $database;

    function __construct( Nette\Database\Context $database )
    {
        $this->database = $database;
    }

    public function renderDefault(): void
    {
        $posts = $this->database->table('posts');
        $this->template->posts = $posts;
    }

    protected function createComponentPostForm(): Form
    {
        $form = new Form;
        $form->onRender[] = 'makeBootstrap4';
        $form->addGroup();
        $form->addText('title', 'Názov:')
            ->setRequired();
        $form->addGroup();
        $form->addTextArea('content', 'Obsah:')
            ->setRequired();
        $form->addGroup();
        $form->addSubmit('send', 'Uložiť a publikovať');
        $form->onSuccess[] = [$this, 'postFormSucceeded'];

        return $form;
    }

    public function postFormSucceeded(Form $form, array $values): void
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->error('Pro vytvoření, nebo editování příspěvku se musíte přihlásit.');
        }

        if( !$this->getUser()->isAllowed('article', 'create') ){
            $this->error('Nemáte dostatočné oprávnenia');
        }
        
        $postId = $this->getParameter('postId');

        if ($postId) {
            $values['edited_by'] = $this->user->getId();
            $post = $this->database->table('posts')->get($postId);
            $post->update($values);
        } else {
            $values['author'] = $this->user->getId();
            $post = $this->database->table('posts')->insert($values);
        }

        $this->flashMessage("Príspevok bol úspešne publikovaný.", 'success');
        $this->redirect(':Front:Post:show', $post->id);
    }

        
    public function actionEdit(int $postId): void
    {
        if ( !$this->getUser()->isLoggedIn() ) {
            $this->redirect('Sign:in');
        } elseif( !$this->getUser()->isAllowed('article', 'edit') ) {
            $this->redirect('Post:show', $postId);
        } else {
            $post = $this->database->table('posts')->get($postId);
            if (!$post) {
                $this->error('Příspěvek nebyl nalezen');
            }
            $this['postForm']->setDefaults($post->toArray());
        }
    }
}