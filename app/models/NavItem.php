<?php
/**
* PHP version 5
*
* @category   Classe NavIten
* @package    NavItem (Modelo)
* @copyright  2014 Sou Digital
*/
class NavItem extends BaseModel{
    //Variável padrão para as regras de validação 
    public static $rules = array(
        'nomeNav' => 'required|min:3|max:255',
        'url' => 'required',       
        'idNav' => 'required|integer'       
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
        'nomeNav.required' => 'O campo Nome &eacute; requerido',       
        'url.required'     => 'O campo URL &eacute; requerido', 
        'idNav.required'   => 'O campo Nav &eacute; requerido',
    );  
    
    /**
     * @desc Faz a ligação com a tabela de Nav, conforme campo idNav da tabela
     * @return Nav $nav
    **/ 
    public function nav()
    {
       return $this->belongsTo('Nav', 'idNav');
    } 
    
    /**
     * @desc Faz a ligação (Muitos para Muitos => nav_item_user) com a tabela de Usuários
     * @return User $usuario
    **/ 
    public function users()
    {
	   return $this->belongsToMany('User');
    }
    
    /**
     * @desc Faz a busca dos NavItems conforme Nav associado e permissão do usuário
     * @param int $id
     * @return NavItem $navItems
    **/  
    public static function findByNav($id = 0)
    {
      
      if( Auth::user()->get()->id == 1){
          $navItems = DB::table('nav_items')
                    ->leftJoin('navs', 'nav_items.idNav', '=', 'navs.id')
              ->where('navs.id', '=', $id)
                      ->select(DB::raw('distinct(navs.id),substring_index(nav_items.url ,"/",1) as controllerNav,  nav_items.id, nav_items.url,nav_items.nomeNav'))
                    ->groupBy('nav_items.nomeNav')
                    ->orderBy('nav_items.nomeNav')
                    ->get();
      }else{
          $navItems = DB::table('nav_items')
                    ->leftJoin('navs', 'nav_items.idNav', '=', 'navs.id')
                    ->join('nav_item_user','nav_item_user.nav_item_id', '=', 'nav_items.id')  
                    ->where('nav_item_user.user_id', '=', Auth::user()->get()->id)
              ->where('navs.id', '=', $id)
                      ->select(DB::raw('distinct(navs.id),substring_index(nav_items.url ,"/",1) as controllerNav,  nav_items.id, nav_items.url,nav_items.nomeNav'))
                    ->groupBy('nav_items.nomeNav')
                    ->orderBy('nav_items.nomeNav')
                    ->get();
      }
      
        return $navItems;
    }
    
    /**
     * @desc Faz a busca dos NavItems conforme Nav associado
     * @param int $id
     * @return NavItem $showUserNavItems
    **/  
    public static function findByNavUser($id = 0)
    {
        $showUserNavItems = DB::table('nav_items')
                                                  ->where('idNav','=',$id)
                                                  ->get();        
        return $showUserNavItems;
    }
    
}
