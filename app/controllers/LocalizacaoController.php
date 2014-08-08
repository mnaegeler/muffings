<?php
/**
* PHP version 5
*
* @category   Controle de Banners do Admin (M�dulos)
* @package    BannerController (Controle de Banners do Admin)
* @copyright  2014 Sou Digital
*/

class LocalizacaoController extends \BaseController
{  
    private static $class = 'Localizacao';
    
    private static $classUrl = 'localizacao';
    
    protected $localizacao;

    protected $estado;

    protected $cidade;
    
    private $storeNav;
    
    private $folder_path;
    
    private $titulo;
    
    private $alturaImagemCrop = 2000;
    
    private $larguraImagemCrop = 691;
    
    private $alturaImagemExibicao = 42;
    
    private $larguraImagemExibicao = 280;
    
    
    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o t�tulo para uso nas Views
     * @param localizacao $localizacao
     * @return void
    **/ 
    public function __construct(Estado $estado,Localizacao $localizacao, Cidade $cidade)
    {       
        parent::__construct();
         
        $this->localizacao = $localizacao;
        $this->estado = $estado;
        $this->cidade = $cidade;
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
            $list = Estado::all();
            //Popula a vari�vel para utiliza��o no t�tulo da p�gina      
            $title = 'Consultar Estados';

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
        
    /* Cadastra Estado*/
    public function postCadastraestado()
    {   
        //Busca todos os inputs que foram postados     
        $input = Input::all();
        //Chama o m�todo de valida��o que consta em models/Pagina
        $validator = Estado::validate($input);
       
       if(isset($input['stateId'])){
           
            $stdClass = Estado::findOrFail( $input['stateId'] );
       }else{
        $stdClass = new Estado();
       }
        
       
        if( $validator->fails() ):
            //Redireciona para a View de Cadastro com as mensagens de erros
            return Redirect::back()
                                  ->withInput()
                                  ->withErrors($validator);
        else:
            foreach( $input as $key => $data ):
                if( $key != '_token' && $key <> 'stateId'):
                    $stdClass->$key = $data;
                endif; 
            endforeach;
        
            $stdClass->save();

             LogAction::createLog($this->titulo, 'inserção', $input['nome'].'( '.$stdClass->id.' )');
            
                 return Redirect::to('admin/'.self::$classUrl.'/editar/'.$stdClass->id.'/')
                                                   ->with('mensagemSucesso', 'Operação realizada com sucesso.');     
            
                                        
        endif;        
        
    }

  /* Exclui estado */  
    public function getExcluirestado($id = '')
    {
        if(!parent::permissao(self::$classUrl)) 
                return Redirect::to('admin/sempermissao');

        $ids = parent::prepareIds($id);
        
        $countIds = count($ids);
        foreach ($ids as $id){
            $getProperties = Estado::findOrFail($id); 
              
            Estado::destroy($id);

            LogAction::createLog($this->titulo, 'exclusão', $getProperties->nome.'( '.$id.' )');
        }
        
        
        return Redirect::to('admin/'.self::$classUrl.'/listar')
                                                ->with('mensagemSucesso',($countIds > 1) ? "Itens excluídos com sucesso!": "Item excluido com sucesso!");
    } 
    

    /* Cadastra Cidade */
    public function postCadastracidade()
    {
        $input = Input::all();
        $input['cidade'] = $input['nome'];
        $validade = Cidade::validate($input);

        

       if($validade->fails()){
          $stdClass = new Cidade();
            $stdClass->idEstado = $input['idEstado'];
            $stdClass->cidade = $input['nome'];
            $stdClass->save();

        return $stdClass->id;
    }else{
        echo 'ja-existe';
    }
      
    }

    /* Exclui Cidade */
    public function postExcluircidade()
    {
        $input = Input::all();
       
        Cidade::destroy($input['id']);
       
    }

    public function postEditarcidade()
    {
        $input = Input::all();
        $cidade = Cidade::find($input['id']);
        $cidade->cidade = $input['nome'];
        $cidade->save();
    }

    public function postListarcidades()
    {
        $input= Input::all();
        $cidades = Cidade::getCidades($input['idEstado']);
        return json_encode($cidades);
    }

}

