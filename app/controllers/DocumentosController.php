<?php
/**
* PHP version 5
*
* @category   Controle de Banners do Admin (M�dulos)
* @package    BannerController (Controle de Banners do Admin)
* @copyright  2014 Sou Digital
*/

class DocumentosController extends \BaseController
{  
    private static $class = 'Documentos';
    
    private static $classUrl = 'documento';
    
    protected $documentos;
    
    private $storeNav;
    
    private $folder_path;
    
    private $titulo;
    
    
    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o t�tulo para uso nas Views
     * @param Banner $banner
     * @return void
    **/ 
    public function __construct(Documentos $documentos)
    {       
        parent::__construct();
         
        $this->documentos = $documentos;
        $this->storeNav = array();  
        //Atributos para pasta de Uploads
        $this->folder_path = public_path().'/uploads/'.self::$classUrl;
        $this->titulo = 'Documentos';
        
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
            
            $list = Documentos::all();
            
            $title = 'Consultar Documentos';

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
        
        
        $title = 'Cadastrar Documento';

        $categorias = categoriasDocumentos::lists('nome','id');
        
        $class = self::$classUrl;
        
         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/cadastrar', 'name' => 'Cadastrar'),
            );
         
        
        return View::make('admin.'.self::$classUrl.'.form', compact('title','max','class', 'breadcrumbs', 'categorias'));
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
       
        $stdClass = self::$class;
        
        $class = self::$classUrl;

        $categorias = categoriasDocumentos::lists('nome','id');

        $data = $stdClass::findOrFail($id);
        
         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/editar/'.$id, 'name' => 'Editar'),
            );
         
        return View::make('admin.'.self::$classUrl.'.form', compact('title', 'data', 'max', 'class', 'breadcrumbs','categorias'));
    }
        
    /**
     * @desc Efetua a inser��o/altera��o do registro
     * @return Redirect
    **/
    public function postStore()
    {   
        //Busca todos os inputs que foram postados     
        $input = Input::all();
        
        $validator = $this->documentos->validate($input);
       
        $new = true;
        
       
        if( Input::has('id') ):
            
            $new = false;
            
            $stdClass = self::$class;
            
            $stdClass = $stdClass::findOrFail( Input::get('id') );
        else:   
           
            $stdClass = new self::$class;
        endif;
        
        
        if( $validator->fails() ):
            return Redirect::back()
                                  ->withInput()
                                  ->withErrors($validator);

        else:

            //Itera entre os dados do input enviados
            foreach( $input as $key => $data ):
                
          
                if( $key != '_token' && $key <> 'arquivo'):    
                    $stdClass->$key = $data;
                endif; 
            endforeach;
            
            if( Input::hasFile('arquivo') ):
                if(!$new && $stdClass->arquivo <> ''){
                     parent::destroyFile($this->folder_path.'/'.$stdClass->id, $stdClass->arquivo);
                }
                $extensao =  Input::file('arquivo')->getClientOriginalExtension();
                $arquivoName = Str::slug($input['nome']).'-'.date('Yis').'.'.$extensao;
                 $stdClass->arquivo = $arquivoName;
                endif;

           
           


            $stdClass->save();

            if(isset($arquivoName)){
                 parent::prepareDiretory($this->folder_path.'/'.$stdClass->id);
                Input::file('arquivo')->move($this->folder_path.'/'.$stdClass->id, $arquivoName );
            }
           

            $log = ($new) ? LogAction::createLog($this->titulo, 'inserção', $input['nome'].'( '.$stdClass->id.' )') : LogAction::createLog($this->titulo, 'edição', $input['nome'].' ( '.$stdClass->id.' )');            
           
            
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

