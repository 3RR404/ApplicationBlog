<?php

namespace App\Model;

use \Nette\Security\Permission;

class AuthorizatorFactory
{
	public static function create(): Permission
	{
		$acl = new Permission;

		// pokud chceme, můžeme role a zdroje načíst z databáze
		$acl->addRole('guest');
		$acl->addRole('subscriber');
		$acl->addRole('admin', 'subscriber');

		$acl->addResource('article');
		$acl->addResource('comment');
		$acl->addResource('Admin:Default');
		$acl->addResource('Admin:Post');
		// $acl->addResource('view');
		// $acl->addResource('edit');
		// $acl->addResource('create');

		$acl->allow('guest', ['article', 'comment'], 'view');
		$acl->deny('guest', 'article', ['edit','create','add']);
		$acl->allow('subscriber', ['Admin:Default','Admin:Post'], 'default');
		$acl->allow('admin');

		// případ A: role admin má menší váhu než role guest
		// $acl->addRole('administrator', ['admin', 'guest']);
		// $acl->isAllowed('administrator', 'article'); // false

		// // případ B: role admin má větší váhu než guest
		// $acl->addRole('mary', ['guest', 'admin']);
		// $acl->isAllowed('mary', 'backend'); // true

		return $acl;
	}
}