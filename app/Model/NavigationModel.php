<?php

namespace App\Model;

use Nette;

class NavigationModel
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    function __construct( Nette\Database\Context $database )
    {
        $this->database = $database;
    }

    public function getMenuItems()
    {
        return $this->database->table('navigation_item')
            ->where('active', 1)
            ->order('pos ASC');
        
        // return array(
        //     'name' => 'item',
        // );
    }
}