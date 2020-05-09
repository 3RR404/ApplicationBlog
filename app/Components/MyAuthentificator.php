<?php

use Nette;
use Nette\Security as NS;

class MyAuthenticator implements NS\IAuthenticator
{
	private $database;

	private $passwords;

	public function __construct(Nette\Database\Context $database, Nette\Security\Passwords $passwords)
	{
		$this->database = $database;
		$this->passwords = $passwords;
	}

	public function authenticate(array $credentials): Nette\Security\IIdentity
	{
		[$username, $password] = $credentials;

		$row = $this->database->table('users')
			->where('username', $username)->fetch();

		if (!$row) {
            if( !$this->database->table('users')->where('useremail', $username)->fetch() )
                throw new Nette\Security\AuthenticationException('User not found.');
            else $row = $this->database->table('users')->where('useremail', $username)->fetch();
		}

		if (!$this->passwords->verify($password, $row->password)) {
			throw new Nette\Security\AuthenticationException('Invalid password.');
		}

		return new Nette\Security\Identity($row->id, $row->role, ['username' => $row->username]);
	}
}