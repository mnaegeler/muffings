<?php
/**
* PHP version 5
*
* @category   Controle de Banners do Admin (M�dulos)
* @package    BannerController (Controle de Banners do Admin)
* @copyright  2014 Sou Digital
*/

class PresskitController extends \BaseController
{  
    private static $class = 'Presskit';
    
    private static $classUrl = 'press-kit';
    
    protected $presskit;
    
    private $storeNav;
    
    private $folder_path;
    
    private $titulo;
    
    
    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o t�tulo para uso nas Views
     * @param Banner $banner
     * @return void
    **/ 
    public function __construct(Presskit $presskit)
    {       
        parent::__construct();
         
        $this->presskit = $presskit;
        $this->storeNav = array();  
        //Atributos para pasta de Uploads
        $this->folder_path = public_path().'/uploads/'.self::$classUrl;
        $this->titulo = 'Documebtos';
        
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
            
            $data = PressKit::first();
            
            $title = 'Alterar Press Kit';

            $class = self::$classUrl;


            
            $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
            );
            
            
            //Popula a View com os resultados da busca dos registros e o t�tulo da p�gina
            return View::make('admin.'.self::$classUrl.'.form', compact('data', 'title', 'class', 'breadcrumbs'));  
    }
        
    /**
     * @desc Efetua a inser��o/altera��o do registro
     * @return Redirect
    **/
    public function postStore()
    {   
        //Busca todos os inputs que foram postados     
        $input = Input::all();
        
        $validator = $this->presskit->validate($input);
        
        
        if( $validator->fails() ):
            return Redirect::back()
                                  ->withInput()
                                  ->withErrors($validator);

        else:

           $stdClass = PressKit::find(1);

            if( Input::hasFile('arquivo') ):
                if($stdClass->arquivo <> ''){
                     parent::destroyFile($this->folder_path, $stdClass->arquivo);
                }
                 $extensao =  Input::file('arquivo')->getClientOriginalExtension();
                $arquivoName = 'Press-Kit-'.date('Yis').'.'.$extensao;
                endif;

           
            $stdClass->arquivo = $arquivoName;


            $stdClass->save();

            Input::file('arquivo')->move($this->folder_path, $arquivoName );

            LogAction::createLog($this->titulo,$arquivoName ,'edição ( '.$stdClass->id.' )');            
           
            
                 return Redirect::to('admin/'.self::$classUrl.'/listar')
                                                   ->with('mensagemSucesso', 'Altera&ccedil;&atilde;o realizada com sucesso.');     
            
                                        
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

