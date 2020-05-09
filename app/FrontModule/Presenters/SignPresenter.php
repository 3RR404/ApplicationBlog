<?php

namespace App\FrontModule\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;


class SignPresenter extends Nette\Application\UI\Presenter
{
    private $authenticator;

    private $database;

    public function __construct( \App\MyAuthenticator $authenticator, Nette\Database\Context $database )
    {
        $this->authenticator = $authenticator;
        $this->database = $database;
    }

    protected function createComponentSignInForm(): Form
    {
        $form = new Form;
        $form->addText('username', 'Užívateľské meno:')
            ->setRequired('Prosím vyplňte svoje užívateľské meno.');
    
        $form->addPassword('password', 'Heslo:')
            ->setRequired('Prosím vyplňte svoje heslo.');
    
        $form->addSubmit('send', 'Prihlásiť');
    
        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;
    }

    public function signInFormSucceeded(Form $form, \stdClass $values): void
    {
        $user = $this->getUser();
        $user->setAuthenticator($this->authenticator);

        try {
            $user->login($values->username, $values->password);
            $this->redirect('Base:');

        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Nesprávne prihlasovacie meno alebo heslo.');
        }
    }

    protected function createComponentSignUpForm(): Form
    {
        $form = new Form;
        $form->addText('username', 'Užívateľské meno:')
            ->setRequired('Užívateľské meno je povinné!');
        $form->addEmail('email', 'E-mail:');
        $form->addPassword('password', 'Heslo:')
            ->setRequired('Prosím vyplňte svoje heslo.');
        $form->addPassword('password_again', 'Heslo znovu:')
            ->setRequired('Prosím potvrďťe svoje heslo.');
        
        $form->addSubmit('send', 'Sign Up');

        $form->onSuccess[] = [$this, 'signUpFormSucceeded'];
        return $form;
    }

    public function signUpFormSucceeded( Form $form, \stdClass $value ): void
    {
        if( $value->password !== $value->password_again ):
            $form->addError('Heslá sa nezhodujú!');
        else:
            $passwords = new Passwords(PASSWORD_BCRYPT, ['cost' => 12]);
            $pass_hash = $passwords->hash( $value->password );

            $data = [
                'username' => $value->username,
                'useremail' => @$value->email?:null,
                'password'  => $pass_hash,
                'role'      => 'guest'
            ];
    
            $e_username = $this->database->table('users')->where('username', $value->username )->fetch('username');
            $e_useremail = $this->database->table('users')->where('useremail', $value->email )->fetch('useremail');
    
            if( $e_username !== null ):
                $this->flashMessage('Username exists!');
            endif;
    
            if( $e_useremail !== null ):
                $this->flashMessage('Useremail already in use!');
            endif;
    
            if( $e_username == null && $e_useremail == null ):
                $this->database->table('users')->insert( $data );
                $this->flashMessage('Your account was created.');
                $this->redirect('Sign:in');
            endif;

        endif;

    }

    public function actionOut(): void
    {
        $this->getUser()->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.');
        $this->redirect('Base:');
    }
}