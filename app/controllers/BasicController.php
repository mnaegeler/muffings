<?php
/**
* PHP version 5
*
* @category   Controle de Páginas para SEO (Módulos)
* @package    PaginaController (Controle de Páginas para SEO )
* @copyright  2014 Sou Digital
*/
class BasicController extends \BaseController
{
    
    private static $class = 'basic';
    
    private static $classUrl = 'basic';
    
    protected $pagina;

    private $titulo;

    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o título para uso nas Views
     * @param Pagina $pagina
     * @return void
    **/ 
    public function __construct(Basic $basic)
    {
        parent::__construct();
        $this->basic = $basic;
        $this->titulo = 'Configurações Básicas';
    }
    
    /**
     * @desc Lista os registros de Paginas e popula os resultados na View
     * @return View
    **/
    public function getListar()
    {            
        //Popula a variável para utilização no título da página
        $title = 'Editar '.$this->titulo;
        
        $stdClass = self::$class;
        $data = $stdClass::find(1);
        
        $class = self::$classUrl;
        
        $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
            );
        
        //Popula a View com os resultados da busca dos registros e o título da página
        return View::make('admin.'.self::$class.'.listar', compact('list', 'title', 'class', 'breadcrumbs','data'));
    }

    /**
     * @desc Inclui/Altera o código do Analytics na tabela correspondente
     * @return View
    **/
    public function postStore()
    {
        
        $input = Input::all();
        if($input){
            $stdClass = self::$class;
            $stdClass = $stdClass::findOrFail( 1 );
            foreach( $input as $key => $data ):
                if( $key != '_token' ):
                    $stdClass->$key = $data;
                endif; 
            endforeach;

            $stdClass->save();

            //Redireciona para a página de listagem informando a mensagem de sucesso
            return Redirect::to('admin/'.self::$classUrl.'/listar')
                                                   ->with('mensagemSucesso', 'Altera&ccedil;&atilde;o realizada com sucesso!');
        }
            
    }
}