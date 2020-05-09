<?php 

namespace App\AdminModule\Presenters;

// use Nette;
// use App\Model\ArticleManager;
use \Nette\Security\User;
use \Nette\Application\UI\Presenter;

class BasePresenter extends Presenter
{
    public function startup()
    {
        parent::startup();

		if (!$this->getUser()->isLoggedIn()) {
            if ($this->user->getLogoutReason() === User::INACTIVITY) {
                $this->flashMessage('Session timeout, you have been logged out');
            }
            $this->redirect(':Front:Sign:in', [
                'backlink' => $this->storeRequest()
            ]);
        } else {
            if (!$this->user->isAllowed($this->name, $this->action)) {
                $this->flashMessage('Access denied');
                $this->redirect(':Base:');
            }
        }
    }

    /**
	 * Logout user
	 */
	public function handleLogout()
	{
		$this->user->logOut();
		$this->flashMessage('You were logged off.');
		$this->redirect('this');
	}

    public function renderDefault(): void
    {
        $this->template->posts = '';
    }


	/** @var \App\Components\INavigationControlFactory @inject */
	public $navigationControlFactory;

	protected function createComponentNavigation()
	{
		return $this->navigationControlFactory->create();
	}
    
}