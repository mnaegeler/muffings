<?php
/**
* PHP version 5
*
* @category   Controle de Banners do Admin (M�dulos)
* @package    BannerController (Controle de Banners do Admin)
* @copyright  2014 Sou Digital
*/

class AreaController extends \BaseController
{  
    private static $class = 'Area';
    
    private static $classUrl = 'area';
    
    protected $area;
    
    private $storeNav;
    
    private $folder_path;
    
    private $titulo;
    
    private $alturaImagemCrop = 2000;
    
    private $larguraImagemCrop = 691;
    
    private $alturaImagemExibicao = 42;
    
    private $larguraImagemExibicao = 280;
    
    
    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o t�tulo para uso nas Views
     * @param area $area
     * @return void
    **/ 
    public function __construct(Area $area)
    {       
        parent::__construct();
         
        $this->area = $area;
        $this->storeNav = array();  
        //Atributos para pasta de Uploads
        $this->folder_path = public_path().'/uploads/'.self::$classUrl;
        $this->titulo = 'Cobertura';
        
        //Verificar se existe pasta de banners em uploads, sen�o, cria uma
        if (!file_exists($this->folder_path))
            mkdir($this->folder_path, 0777);
    }
    
    /**
     * @desc Lista os registros de Banners e popula os resultados na View
     * @return View
    **/
    public function getListar()
    {
            if(!parent::permissao(self::$classUrl)) 
                return Redirect::to('admin/sempermissao');
            
             //Busca todos os registros da tabela de Banners   
            $list = Area::all();
            //Popula a vari�vel para utiliza��o no t�tulo da p�gina      
            $title = 'Consultar Área';

            $class = self::$classUrl;
            
            $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
            );
            
            //Popula a View com os resultados da busca dos registros e o t�tulo da p�gina
            return View::make('admin.'.self::$classUrl.'.listar', compact('list', 'title', 'class', 'breadcrumbs'));
       
       
    }
    
    public function getCadastrar()
    {
       if(!parent::permissao(self::$classUrl)) 
                return Redirect::to('admin/sempermissao');
        
        
        //Atribui o t�tulo da P�gina � vari�vel $title
        $title = 'Cadastrar Estado';
        //Busca o n�mero para formar a ordem do banner
        $max   = (Banner::max('ordem'))+1;
        //Popula as informa��es de $title na View Form   
        
        $class = self::$classUrl;
        
         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/cadastrar', 'name' => 'Cadastrar Estado'),
            );
         
        
        return View::make('admin.'.self::$classUrl.'.cadastrar', compact('title','max','class', 'breadcrumbs','alturaImagemCrop', 'larguraImagemCrop'));
    }   
    
    /**
     * @desc Popula as informa��es para a View Banner\Form, se o mesmo for uma edi��o (Edi��o)
     * @param $id
     * @return View
    **/
    public function getEditar($id = '')
    {   
        
        if(!parent::permissao(self::$classUrl)) 
                return Redirect::to('admin/sempermissao');
        
        $title = 'Editar '.$this->titulo;    
        
         $estado = Estado::find($id);

        $class = self::$classUrl;
        
        
         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/editar/'.$id, 'name' => 'Editar'),
            );
         
        
        return View::make('admin.'.self::$classUrl.'.alterar', compact('title', 'class', 'breadcrumbs', 'estado'));
    }
        
     /**
     * @desc Efetua a inserção/alteração do registro
     * @return Redirect
     **/
    public function postStore()
    {
        //Busca todos os inputs que foram postados        
        $input = Input::all();
        //Chama o método de validação que consta em models/Nav
        $validator = $this->area->validate($input);
        //Inicializa a variável $new para posterior verificação se é um registro novo ou de alteração
        $new = true;
        
        //Verifica se existe input hidden de id
        if( Input::has('id') ):
            //Se existe, é uma alteração, então, atribuímos false para a variável
            $new = false;
            //Atribui variável $stdClass a classe descrita na variável estática $class
            $stdClass = self::$class;
            //Faz a busca para retornar os dados já inseridos
            $stdClass = $stdClass::findOrFail( Input::get('id') );
        else:    
            //Se é um registro novo, inicializa a classe especificada
            $stdClass = new self::$class;
        endif;
        
        //Se houve problemas na validação das informações postadas nos inputs
        if( $validator->fails() ):
            //Redireciona para a View de Cadastro com as mensagens de erros
            return Redirect::back()
                                  ->withInput()
                                  ->withErrors($validator);
        else:
            //Itera entre os dados do input enviados
            foreach( $input as $key => $data ):
                //Verifica se o name é diferente de _token (este input é utilizado como padrão para prevenção de injections), não sendo necessária inserção
                if( $key != '_token'):
                    $stdClass->$key = $data;
                endif; 
            endforeach;
           
            $stdClass->save();
            $log = ($new) ? LogAction::createLog($this->titulo, 'inserção', $input['nome'].'( '.$stdClass->id.' )') : LogAction::createLog($this->titulo, 'edição', $input['nome'].' ( '.$stdClass->id.' )');

            return Redirect::to('admin/email-contato/editar/'.$stdClass->id.'/')
                                                   ->with('mensagemSucesso', $new ? 'Cadastro realizado com sucesso!' : 'Altera&ccedil;&atilde;o realizada com sucesso!');                                  
        endif;        
        
    }

    public function getExcluir($id = '')
    {

        if(!parent::permissao(self::$classUrl))
                return Redirect::to('admin/sempermissao');

        $ids = parent::prepareIds($id);
        
        $countIds = count($ids);
        foreach ($ids as $id){
            $stdClass = self::$class;
            $getProperties = $stdClass::findOrFail($id); 

                
            $stdClass::destroy($id);
          
            LogAction::createLog($this->titulo, 'exclusão', $getProperties->nome.'( '.$id.' )');
            //Redireciona para a View de Listagem, com a mensagem de sucesso
        }
        
        
        return Redirect::to('admin/'.self::$classUrl.'/listar')
                                                ->with('mensagemSucesso',($countIds > 1) ? "Itens excluídos com sucesso!": "Item excluido com sucesso!");
    } 

}

