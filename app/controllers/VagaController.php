<?php
/**
* PHP version 5
*
* @category   Controle de Banners do Admin (M�dulos)
* @package    BannerController (Controle de Banners do Admin)
* @copyright  2014 Sou Digital
*/

class VagaController extends \BaseController
{  
    private static $class = 'Vaga';
    
    private static $classUrl = 'vaga';
    
    protected $vaga;
    
    private $storeNav;
    
    private $folder_path;
    
    private $titulo;    
    
    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o t�tulo para uso nas Views
     * @param vaga $vaga
     * @return void
    **/ 
    public function __construct(Vaga $vaga)
    {       
        parent::__construct();
         
        $this->vaga = $vaga;
        $this->storeNav = array();  
        //Atributos para pasta de Uploads
        $this->folder_path = public_path().'/uploads/'.self::$classUrl;
        $this->titulo = 'Serviços';
        
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
            $list = Vaga::all();
            //Popula a vari�vel para utiliza��o no t�tulo da p�gina      
            $title = 'Consultar Serviços';

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
        
        $title = 'Cadastrar Vaga'; 
        $class = self::$classUrl;
        
         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/cadastrar', 'name' => 'Cadastrar'),
            );
         
        return View::make('admin.'.self::$classUrl.'.form', compact('title','class', 'breadcrumbs'));
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
        
        //Atribui o t�tulo da P�gina � vari�vel $title
        $title = 'Editar '.$this->titulo;    
        //Atribui a $stdClass a classe especificada na vari�vel est�tica acima
        $stdClass = self::$class;
        
        $class = self::$classUrl;
        //Atribui a vari�vel $data ao registro buscado na tabela, conforme o c�digo
        $data = $stdClass::findOrFail($id);

         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/editar/'.$id, 'name' => 'Editar'),
            );
    
        return View::make('admin.'.self::$classUrl.'.form', compact('title', 'data', 'max', 'class', 'breadcrumbs'));
    }
        
    /**
     * @desc Efetua a inser��o/altera��o do registro
     * @return Redirect
    **/
    public function postStore()
    {   
        //Busca todos os inputs que foram postados     
        $input = Input::all();
        //Chama o m�todo de valida��o que consta em models/Pagina
        $validator = $this->vaga->validate($input);
        //Inicializa a vari�vel $new para posterior verifica��o se � um registro novo ou de altera��o
        $new = true;
        
        //Verifica se existe input hidden de id
        if( Input::has('id') ):
            //Se existe, é uma altera��o, ent�o, atribu�mos false para a vari�vel
            $new = false;
            //Atribui vari�vel $stdClass a classe descrita na vari�vel est�tica $class
            $stdClass = self::$class;
            //Faz a busca para retornar os dados j� inseridos
            $stdClass = $stdClass::findOrFail( Input::get('id') );
        else:   
            //Se � um registro novo, inicializa a classe especificada 
            $stdClass = new self::$class;
        endif;
        
        //Se houve problemas na valida��o das informa��es postadas nos inputs
        if( $validator->fails() ):
            //Redireciona para a View de Cadastro com as mensagens de erros
            return Redirect::back()
                                  ->withInput()
                                  ->withErrors($validator);
        else:
            parent::prepareDiretory($this->folder_path.'/'.$stdClass->id);


          
            foreach( $input as $key => $data ):
                if( $key != '_token' ):
                    $stdClass->$key = $data;
                endif; 
            endforeach;

            $stdClass->save();
       


            $log = ($new) ? LogAction::createLog($this->titulo, 'inserção', $input['nome'].'( '.$stdClass->id.' )') : LogAction::createLog($this->titulo, 'edição', $input['nome'].' ( '.$stdClass->id.' )');            
           
            
            //Redireciona para a listagem com a mensagem de sucesso, conforme o tipo de operação
            
                 return Redirect::to('admin/'.self::$classUrl.'/editar/'.$stdClass->id.'/')
                                                   ->with('mensagemSucesso', $new ? 'Cadastro realizado com sucesso.' : 'Altera&ccedil;&atilde;o realizada com sucesso.');     
            
                                        
        endif;        
        
    }

  /**
     * @desc faz a exclusão de um ou mais registros
     * @param $ids
     * @return Redirect
    **/   
    public function getExcluir($id = '')
    {

        if(!parent::permissao(self::$classUrl))
                return Redirect::to('admin/sempermissao');

        $ids = parent::prepareIds($id);
        
        $countIds = count($ids);
        foreach ($ids as $id){
                //Atribui a $stdClass a classe especificada na vari�vel est�tica acima
            $stdClass = self::$class;
            //Atribui a vari�vel $getProperties ao registro buscado na tabela, conforme o c�digo
            $getProperties = $stdClass::findOrFail($id); 

            parent::destroyDiretory($this->folder_path.'/'.$getProperties->id);

            
            //Exclui o valor da tabela       
            $stdClass::destroy($id);
            //Cria log da a��o de exclus�o
            LogAction::createLog($this->titulo, 'exclusão', $getProperties->nome.'( '.$id.' )');
            //Redireciona para a View de Listagem, com a mensagem de sucesso
        }
        
        
        return Redirect::to('admin/'.self::$classUrl.'/listar')
                                                ->with('mensagemSucesso',($countIds > 1) ? "Itens excluídos com sucesso!": "Item excluido com sucesso!");
    } 
    
}

