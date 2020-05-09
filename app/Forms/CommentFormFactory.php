<?php

declare(strict_types=1);

namespace App\Forms;

use Nette;
// use Nette\Forms\Form;
use Nette\Application\UI\Form;
use Nette\Security\User;

// Debugger::enable();

/**
 * BS4 Form Factory
 */
final class CommentFormFactory 
{
    
    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var User */
    private $user;
    
    public function __construct(FormFactory $factory, User $user)
	{
		$this->factory = $factory;
		$this->user = $user;
    }
    

    public function create(callable $onSuccess): Form
    {
        $form = new Form;
        $form->onRender[] = 'makeBootstrap4';
        if( !$this->user->isLoggedIn() ){
            $form->addGroup();
            $form->addText('name', 'Meno:')
                ->setRequired();
    
            $form->addEmail('email', 'Email:');
        }
        $form->addGroup();
        $form->addTextArea('content', 'Komentár:')
            ->setRequired();

        $form->addGroup();
        $form->addSubmit('send', 'Publikovať komentár');
        // $form->addSubmit('cancel', 'Cancel');

        $form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
			try {
				$this->user->setExpiration($values->remember ? '14 days' : '20 minutes');
				$this->user->login($values->username, $values->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError('The username or password you entered is incorrect.');
				return;
			}
			$onSuccess();
		};

		return $form;

    }

}