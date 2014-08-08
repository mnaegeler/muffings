<?php
/**
* PHP version 5
*
* @category   Controle de Banners do Admin (M�dulos)
* @package    BannerController (Controle de Banners do Admin)
* @copyright  2014 Sou Digital
*/

class BannerController extends \BaseController
{  
    private static $class = 'Banner';
    
    private static $classUrl = 'banner';
    
    protected $banner;
    
    private $storeNav;
    
    private $folder_path;
    
    private $titulo;

    private $thumbs = array(
        0 => array('largura' => 150, 'altura' => 150),
        1 => array('largura' => 1920, 'altura' => 500),
        2 => array('largura' => 136, 'altura' => 100),
    );
    
    private $alturaImagemCrop = 1920;
    
    private $larguraImagemCrop = 500;
    
    private $alturaImagemExibicao = 42;
    
    private $larguraImagemExibicao = 280;
    
    
    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o t�tulo para uso nas Views
     * @param Banner $banner
     * @return void
    **/ 
    public function __construct(Banner $banner)
    {       
        parent::__construct();
         
        $this->banner = $banner;
        $this->storeNav = array();  
        //Atributos para pasta de Uploads
        $this->folder_path = public_path().'/uploads/'.self::$classUrl;
        $this->titulo = 'Banners';
        
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
            $list = Banner::bannersByOrdem();
            //Popula a vari�vel para utiliza��o no t�tulo da p�gina      
            $title = 'Consultar Banners';

            $class = self::$classUrl;
            
            $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
            );
            
            $larguraExibicao = $this->larguraImagemExibicao;
            $alturaExebicao = $this->larguraImagemExibicao;

            $areas = Area::listagemBannerAdmin();
            
            //Popula a View com os resultados da busca dos registros e o t�tulo da p�gina
            return View::make('admin.'.self::$classUrl.'.listar', compact('list', 'title', 'class', 'breadcrumbs', 'alturaExebicao', 'larguraExibicao','areas'));
       
       
    }
    
    public function getCadastrar()
    {
        if(!parent::permissao(self::$classUrl))
                return Redirect::to('admin/sempermissao');
        
        
        //Atribui o t�tulo da P�gina � vari�vel $title
        $title = 'Cadastrar Banners';
        //Busca o n�mero para formar a ordem do banner
        $max   = (Banner::max('ordem'))+1;
        //Popula as informa��es de $title na View Form   
        
        $class = self::$classUrl;
        
         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/cadastrar', 'name' => 'Cadastrar'),
            );
         
        $areas = DB::table('areas')->where('id','<>',2)->lists('nome','id');

         $alturaImagemCrop = $this->alturaImagemCrop;
         $larguraImagemCrop = $this->larguraImagemCrop;
         
         
        
        return View::make('admin.'.self::$classUrl.'.form', compact('title','max','class', 'breadcrumbs','alturaImagemCrop', 'larguraImagemCrop','areas'));
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
        
        //Busca o n�mero para formar a ordem do banner
        $max   = (Banner::max('ordem'))+1;
        
         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/editar/'.$id, 'name' => 'Editar'),
            );
         
         $areas = DB::table('areas')->where('id','<>',2)->lists('nome','id');

           
         $alturaImagemExibicao = $this->alturaImagemExibicao;
         $larguraImagemExibicao = $this->larguraImagemExibicao;
         $larguraImagemCrop = $this->larguraImagemCrop;
         $alturaImagemCrop = $this->alturaImagemCrop;
         
        
        //Popula as informa��es de $title e $data na View Form 
        //return View::make('admin.banner.form')->with(compact('title'))
                                             // ->with(compact('data'))
                                             // ->with(compact('max'));
        return View::make('admin.'.self::$classUrl.'.form', compact('title', 'data', 'max', 'class', 'breadcrumbs', 'alturaImagemExibicao', 'larguraImagemExibicao', 'alturaImagemCrop','larguraImagemCrop','areas'));
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
        $validator = $this->banner->validate($input);
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
            //Itera entre os dados do input enviados
            foreach( $input as $key => $data ):
                /**
                * Verifica se o name � diferente de _token (este input �utilizado como padr��o para preven��o de injections), al�m do arquivo
                * imagem que n�o s�o necess�rios para a primeira itera��o
                **/
                if( $key != '_token' && $key != 'imagem'):
                    if($key == 'url')
                        $data = parent::prepareUrlHttp($data);
                    
                    $stdClass->$key = $data;
                endif; 
            endforeach;
            
             //Se o campo arquivo estiver preenchido
            if( Input::hasFile('imagem') ):
                 if(!$new && $stdClass->imagem <> ''){
                    parent::destroyThumbs($this->thumbs, $this->folder_path.'/'.$stdClass->id, $stdClass->imagem);
                }
                
                $stdClass->imagem;
                $image = Input::file('imagem');
                $filename = date('YmdHis').'-'.$image->getClientOriginalName();
                $stdClass->imagem = $filename;
            endif;
            
            
            //Salva no banco de dados as informações      
            $stdClass->save();

            parent::prepareDiretory($this->folder_path.'/'.$stdClass->id);

             if(isset($filename)){
                parent::prepareImages($this->thumbs, $image->getRealPath(),$this->folder_path.'/'.$stdClass->id.'/', $filename);
            }
            
            //Verifica se � uma inser��o ou altera��o e cria um registro na tabela de Logs, informando o t�tulo da p�gina, c�digo gerado e input com as informa��es
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
    
    
    public function getOrdenar($ids)
    {
        $ids = parent::prepareIds($ids);
       
        $i =1;
        foreach ($ids as $id){
            $class = self::$class;
            $class = $class::findOrFail($id);
            $class->ordem = $i;
            $class->save();
            $i++;
        }
    }
}

