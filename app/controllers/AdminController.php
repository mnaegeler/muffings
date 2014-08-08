<?php
/**
* PHP version 5
*
* @category   Controle geral do Sistema Administrador
* @package    AdminController (Controle do Sistema Administrador)
* @copyright  2014 Sou Digital
*/
class AdminController extends BaseController
{
    private static $class = 'Admin';
    
    private static $classUrl = 'home';
    
    protected $home;
    
    private $storeNav;
    
    private $folder_path;
    
    private $titulo;
    
       /**
     * @desc Construtor da Classe, retornando os atributos herdados e o t�tulo para uso nas Views
     * @param Banner $banner
     * @return void
    **/ 
    public function __construct(Admin $admin)
    {       
        parent::__construct();
         
        $this->admin = $admin;
        $this->storeNav = array();  
        //Atributos para pasta de Uploads
        $this->titulo = 'Home';
    }
    /**
     * @desc Popula a index (capa), efetuando a verificação se usuário está logado ou não, enviando para a página correspondente     * 
     * @return View
     **/
    public function getIndex()
    {
        $class = self::$classUrl;
        //Se o usuário está autenticado
        if( Auth::user()->check() ):
            $user = User::find(Auth::user()->get()->id);
            $navs = Nav::getNavs();
            $basic = Seo::getInfoBasic(); 

            //Popula a View Home (quando usuário estiver logado), baseada na função getHome deste Controller
            return View::make('admin.home', compact('title', 'breadcrumbs', 'class', 'user', 'navs', 'basic'));
        else: 
            //Caso contrário, popula a View Login, alterando o título da página
            $title = 'Efetuar Login';
            $basic = Seo::getInfoBasic(); 

            return View::make('admin.login', compact('title', 'breadcrumbs', 'basic'));            
        endif;
                
    }
    
    /**
     * @desc Popula a index, caso o usuário esteja logado
     * @param int $user
     * @return void
     **/
    public function getHome($user = '')
    {
        $class = self::$classUrl;
        //Se usuário estiver logado
        if(Auth::user()->check() ):
            //Busca os dados do usuário no banco de dados, conforme registro de login
            $user = User::find(Auth::user()->get()->id);
        
            $breadcrumbs = '';
          
            //Popula a View com a página Home, quando estiver logado, informando os dados do usuário e o título
            return View::make('admin.home', compact('user', 'title', 'breadcrumbs', 'class'));
        else:
            //Caso contrário, redireciona para a página de Login, com a mensagem especifíca            
            return Redirect::to('admin.login')->with('mensagemSucesso', 'Voc&ecirc; deve estar logado!');
        endif;
    }    
    
}