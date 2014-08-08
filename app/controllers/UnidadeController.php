<?php
/**
* PHP version 5
*
* @category   Controle de Banners do Admin (M�dulos)
* @package    BannerController (Controle de Banners do Admin)
* @copyright  2014 Sou Digital
*/

class UnidadeController extends \BaseController
{  
    private static $class = 'Unidade';
    
    private static $classUrl = 'unidade';
    
    protected $unidade;

    protected $estado;

    protected $cidade;
    
    private $storeNav;
    
    private $folder_path;
    
    private $titulo;
    
    
    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o t�tulo para uso nas Views
     * @param unidade $unidade
     * @return void
    **/ 
    public function __construct(Estado $estado,Unidade $unidade, Cidade $cidade)
    {       
        parent::__construct();
         
        $this->estado = $estado;
        $this->cidade = $cidade;
        $this->storeNav = array();  
        //Atributos para pasta de Uploads
        $this->folder_path = public_path().'/uploads/'.self::$classUrl;
        $this->titulo = 'unidade';
        
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
            $list = Unidade::all();
            //Popula a vari�vel para utiliza��o no t�tulo da p�gina      
            $title = 'Consultar Unidades';

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
        $title = 'Cadastrar Unidade';

        $estados = Estado::all();
        
        $class = self::$classUrl;
        
         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/cadastrar', 'name' => 'Cadastrar Unidade'),
            );
         
        
        return View::make('admin.'.self::$classUrl.'.cadastrar', compact('title','max','class', 'breadcrumbs', 'estados'));
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
        
        $estados = Estado::all();
        $unidade = Unidade::find($id);
        $class = self::$classUrl;
        
        
         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/editar/'.$id, 'name' => 'Editar'),
            );
         
        
        return View::make('admin.'.self::$classUrl.'.alterar', compact('title', 'class', 'breadcrumbs','unidade', 'estados'));
    }
        
  
    public function postCadastrar()
    {   
        //Busca todos os inputs que foram postados     
        $input = Input::all();
        //Chama o m�todo de valida��o que consta em models/Pagina
        $validator = Unidade::validate($input);
       
        if( $validator->fails() ):
            //Redireciona para a View de Cadastro com as mensagens de erros
            return Redirect::back()
                                  ->withInput()
                                  ->withErrors($validator);
        else:
             $unidade = new Unidade();
             $unidade->nome = $input['nome'];

             $unidade->endereco = $input['endereco'];
             $unidade->bairro = $input['bairro'];
             $unidade->email = $input['email'];
             $unidade->latitude = $input['latitude'];
             $unidade->longitude = $input['longitude'];
             $unidade->telefone = $input['telefone'];
             $unidade->atendimento = $input['atendimento'];
             $unidade->save();

             $cidades = Cidade::all();

             foreach ($cidades as $cidade){
                 if(isset($input['cidade-'.$cidade->id])){
                    $unidadeCidade = new UnidadeCidade();
                    $unidadeCidade->idUnidade = $unidade->id;
                    $unidadeCidade->idCidade = $cidade->id;
                    $unidadeCidade->save();
                 }
             }


             LogAction::createLog($this->titulo, 'inserção', $input['nome'].'( '.$unidade->id.' )');
            
                 return Redirect::to('admin/'.self::$classUrl.'/editar/'.$unidade->id.'/')
                                                   ->with('mensagemSucesso', 'Cadastro realizado com sucesso.');     
            
                                        
        endif;        
        
    }


    public function postAlterar()
    {
        $input = Input::all();

        $validator = Unidade::validate($input);

        if( $validator->fails() ):
            //Redireciona para a View de Cadastro com as mensagens de erros
            return Redirect::back()
                                  ->withInput()
                                  ->withErrors($validator);
        else:
             $unidade =  Unidade::find($input['id']);

             $unidade->nome = $input['nome'];


             $unidade->endereco = $input['endereco'];
             $unidade->bairro = $input['bairro'];
             $unidade->email = $input['email'];

             $unidade->latitude = $input['latitude'];
             $unidade->longitude = $input['longitude'];
             
             $unidade->telefone = $input['telefone'];
             $unidade->atendimento = $input['atendimento'];
             $unidade->save();

             $unidade->save();

             $cidades = Cidade::all();
             UnidadeCidade::destroiRelacao($input['id']);

             foreach ($cidades as $cidade){
                 if(isset($input['cidade-'.$cidade->id])){
                    $unidadeCidade = new UnidadeCidade();
                    $unidadeCidade->idUnidade = $unidade->id;
                    $unidadeCidade->idCidade = $cidade->id;
                    $unidadeCidade->save();
                 }
             }


             LogAction::createLog($this->titulo, 'Alterar', $input['nome'].'( '.$unidade->id.' )');
            
                 return Redirect::to('admin/'.self::$classUrl.'/editar/'.$unidade->id.'/')
                                                   ->with('mensagemSucesso', 'Alteração efetuada com sucesso.');     
                                      
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
        }
        
        
        return Redirect::to('admin/'.self::$classUrl.'/listar')
                                                ->with('mensagemSucesso',($countIds > 1) ? "Itens excluídos com sucesso!": "Item excluido com sucesso!");
    } 


}

