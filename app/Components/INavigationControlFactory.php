<?php

namespace App\Components;

interface INavigationControlFactory
{
	/**
	 * @return NavigationControl
	 */
	public function create();
}