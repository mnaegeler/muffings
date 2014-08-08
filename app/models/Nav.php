<?php
/**
* PHP version 5
*
* @category   Classe Nav
* @package    Nav (Modelo)
* @copyright  2014 Sou Digital
*/
class Nav extends BaseModel { 
	//Variável padrão para as regras de validação
    public static $rules = array(
        'nome'    => 'required|min:3|max:255',
        'idOrdem' => 'required|integer'       
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'nome.required'    => 'O campo Nome &eacute; requerido',    	
    	'idOrdem.required' => 'O campo Ordem &eacute; requerido', 
    	'idOrdem.integer'  => 'O campo Ordem dever&aacute; ser um inteiro',
    );  

    public static function getNavs()
    {
        if(Auth::user()->check()){
            if(Auth::user()->get()->id == 1){
                return DB::table('navs')->orderBy('idOrdem')->get();
            }else{
                return DB::table('nav_items')
                ->leftJoin('navs', 'nav_items.idNav', '=', 'navs.id')
                ->join('nav_item_user','nav_item_user.nav_item_id', '=', 'nav_items.id')    
                ->where('nav_item_user.user_id', '=', Auth::user()->get()->id)
                ->groupBy('navs.id')
                ->orderBy('nav_items.nomeNav')
                ->get();
            }
        }
    }

    public static function listNavs()
    {
        return DB::table('navs')->orderBy('idOrdem')->get();
    }
}
