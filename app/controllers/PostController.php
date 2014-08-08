<?php
/**
* PHP version 5
*
* @category   Controle de Clientes do Admin (M�dulos)
* @package    ClienteController (Controle de Cliente do Admin)
* @copyright  2014 Sou Digital
*/
class PostController extends \BaseController
{  
    private static $class = 'Post';
    
    private static $classUrl = 'post';
    
    protected $cliente;
    
    private $storeNav;
    
    private $folder_path;
    
    private $titulo;
 
    
    private $thumbs = array(
        0 => array('largura' => 220, 'altura' => 165),
        1 => array('largura' => 300, 'altura' => 222),
        2 => array('largura' => 150, 'altura' => 150),
        3 => array('largura' => 800, 'altura' => 600),
        4 => array('largura' => 140, 'altura' => 140),
    );
  
    private $larguraImagemCropGrande = 620;
    private $alturaImagemCropGrande = 305;
    

    
    private $alturaImagemExibicao = 150;
    
    private $larguraImagemExibicao = 150;
    
    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o t�tulo para uso nas Views
     * @param Categorias $categorias
     * @return void
    **/ 
    public function __construct(Post $post)
    {       
        parent::__construct();
         
        $this->post = $post;
        $this->storeNav = array();  
        //Atributos para pasta de Uploads
        $this->folder_path = public_path().'/uploads/'.self::$classUrl;
        $this->titulo = 'Post';
        
        //Verificar se existe pasta de banners em uploads, sen�o, cria uma
        if (!file_exists($this->folder_path))
            mkdir($this->folder_path, 0777);
    }
    
    /**
     * @desc Lista os registros e popula os resultados na View
     * @return View
    **/
    public function getListar()
    {
        
            if(!parent::permissao(self::$classUrl))
                return Redirect::to('admin/sempermissao');
            
             //Busca todos os registros da tabela de Banners   
            $list = Post::all();
            //Popula a vari�vel para utiliza��o no t�tulo da p�gina      
            $title = 'Consultar Posts';

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
        $title = 'Cadastrar '.$this->titulo;
        //Popula as informa��es de $title na View Form   
        
        $class = self::$classUrl;
        
        $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/cadastrar', 'name' => 'Cadastrar '),
            );
        
        $larguraImagemCrop = $this->larguraImagemCropGrande;
        $alturaImagemCrop = $this->alturaImagemCropGrande;
         $categorias = CategoriasPosts::lists('nome','id');       
        
        return View::make('admin.'.self::$classUrl.'.form', compact('title','class', 'breadcrumbs','alturaImagemCrop','larguraImagemCrop','categorias'));
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
         
         $alturaImagemExibicao = $this->alturaImagemExibicao;
         $larguraImagemExibicao = $this->larguraImagemExibicao;
         $larguraImagemCrop = $this->larguraImagemCropGrande;
         $alturaImagemCrop = $this->alturaImagemCropGrande;
         $categorias = CategoriasPosts::lists('nome','id');                
                         
        
        
        //Popula as informa��es de $title e $data na View Form 
        //return View::make('admin.banner.form')->with(compact('title'))
                                             // ->with(compact('data'))
                                             // ->with(compact('max'));
        return View::make('admin.'.self::$classUrl.'.form', compact('title', 'data','class', 'breadcrumbs', 'alturaImagemExibicao', 'larguraImagemExibicao', 'alturaImagemCrop','larguraImagemCrop','categorias'));
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
        $validator = $this->post->validate($input);
        //Inicializa a vari�vel $new para posterior verifica��o se � um registro novo ou de altera��o
        $new = true;
        
        //Verifica se existe input hidden de id
        if( Input::has('id')):
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
                    if($key == 'data')
                        $data = parent::parseDate($data,'Y-m-d');
                        
                    if($key == 'nome')
                         $stdClass->slug = Str::slug($data);
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
            //Salva no banco de dados as informa��es   
           
            $stdClass->save();

             parent::prepareDiretory($this->folder_path.'/'.$stdClass->id);

            
            if(isset($filename)){
                parent::prepareImages($this->thumbs,$image->getRealPath(), $this->folder_path.'/'.$stdClass->id, $filename);
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
        $ids = parent::prepareIds($id);

        $countIds = count($ids);
        foreach ($ids as $id) {
            //Atribui a $stdClass a classe especificada na vari�vel est�tica acima
            $stdClass = self::$class;
            //Atribui a vari�vel $getProperties ao registro buscado na tabela, conforme o c�digo
            $getProperties = $stdClass::findOrFail($id);

            // Apaga as imagens 
            parent::destroyDiretory($this->folder_path.'/'.$getProperties->id);
            //Exclui o valor da tabela       
            $stdClass::destroy($id);
            //Cria log da a��o de exclus�o
            LogAction::createLog($this->titulo, 'exclusão', $getProperties->nome . '( ' . $id . ' )');
            //Redireciona para a View de Listagem, com a mensagem de sucesso
        }
        return Redirect::to('admin/' . self::$classUrl . '/listar')
                        ->with('mensagemSucesso', ($countIds > 1) ? "Itens excluídos com sucesso!" : "Item excluido com sucesso!");
    } 


    public function postImage($id) 
    {

        $path =  $this->public_path . (int)$blogId;

        $image = Input::file('photo');

        if (Input::hasFile('photo'))
        {
            $fileName = $file->getClientOriginalName();

            $image->move($path,$fileName);
            $image = new Image;
            $image->name = $fileName.name;
            $image->save();

            // resizing an uploaded file
            Image::make($image->getRealPath())->resize(300, 200)->save($path.'thumb-'.$fileName);
            Image::make($image->getRealPath())->resize(300, 200)->save($path.'thumb-'.$fileName);

            // Return Image path as JSON
           if ($file->move($path, $fileName))
           {
               return Response::json(array('filelink' => $path . '/' . $fileName));
           }
        }
    }

     public function postImageredactor($id) 
    {
        //var_dump(Input::all());exit;
        $path =  $this->folder_path  .'/'.(int)$id.'/';
     
        $image = Input::file('file');

        if (Input::hasFile('file'))
        {
            $fileName = md5(date('Yis')).'-'.Str::slug($image->getClientOriginalName());

           if ($image->move($path, $fileName))
           {
               return Response::json(array('filelink' => url() .'/uploads/'. self::$class. '/' .$id.'/'. $fileName));
           }
        }
    }

}

