<?php
/**
* PHP version 5
*
* @category   Classe NavItemUser
* @package    NavItemUser (Modelo)
* @copyright  2014 Sou Digital
*/
class Nivel extends BaseModel {
    
    public $timestamps = false;
    //Informa que a tabela nav_item_user fará a associação entre NavItem e User
    protected $table = 'nav_item_user';
}

