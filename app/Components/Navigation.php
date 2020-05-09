<?php

namespace App\Components;

use Nette;
use Nette\Application\UI\Control;
use App\Model\NavigationModel;

class NavigationControl extends Control
{
	/** @var NavigationModel */
	private $navigationModel;

	public function __construct(NavigationModel $navigationModel)
	{
		$this->navigationModel = $navigationModel;
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/templates/navigation.latte');
		$this->template->items = $this->navigationModel->getMenuItems();
		$this->template->render();
	}

	// public function renderBreadcrumbs()
	// {
	// 	$this->template->setFile(__DIR__ . '/breadcrumbs.latte');
	// 	$this->template->items = $this->navigationModel->getBreadcrumbsItems();
	// 	$this->template->render();
	// }
}