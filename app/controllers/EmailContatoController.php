<?php
/**
* PHP version 5
*
* @category   Controle de EmailContato (Módulos)
* @package    EmailContatoController (Controle de Navs)
* @copyright  2014 Sou Digital
*/
class EmailContatoController extends \BaseController
{
    
    private static $class = 'EmailContato';
    
    private static $classUrl = 'email-contato';
    
    protected $emailcontato;

     private $thumbs = array(
        0 => array('largura' => 150, 'altura' => 150),
        1 => array('largura' => 200, 'altura' => 80),
    );

    private $alturaImagemCrop = 200;
    
    private $larguraImagemCrop = 80;

    private $titulo;

    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o título para uso nas Views
     * @param EmailContato $emailcontato
     * @return void
     **/
    public function __construct(EmailContato $emailcontato)
    {
        if(parent::permissao(self::$classUrl)){
            parent::__construct();
            $this->emailcontato = $emailcontato;
            $this->titulo = 'E-mail Contato';
             $this->folder_path = public_path().'/uploads/'.self::$classUrl;

             //Verificar se existe pasta de banners em uploads, sen�o, cria uma
        if (!file_exists($this->folder_path))
            mkdir($this->folder_path, 0777);

        }
        
    }
    
    /**
     * @desc Lista os registros de EmailContato e popula os resultados na View
     * @return View
     **/
    public function getListar()
    {
        //Busca todos os registros da tabela de EmailContato
        $list = EmailContato::all();
        //Popula a variável para utilização no título da página
        $title = 'Consultar '.$this->titulo;
        
        $class = self::$classUrl;
        
        $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
            );
        
        //Popula a View com os resultados da busca dos registros e o título da página
        return View::make('admin.'.self::$classUrl.'.listar', compact('list', 'title', 'class', 'breadcrumbs'));
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
        $validator = $this->emailcontato->validate($input);
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
                if( $key != '_token' && $key != 'imagem'):
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

            //Verifica se é uma inserção ou alteração e cria um registro na tabela de Logs, informando o título da página, código gerado e input com as informações
            $log = ($new) ? LogAction::createLog($this->titulo, 'inserção', $input['pagina'].'( '.$stdClass->id.' )') : LogAction::createLog($this->titulo, 'edição', $input['pagina'].' ( '.$stdClass->id.' )');

            //Redireciona para a listagem com a mensagem de sucesso, conforme o tipo de operação
            return Redirect::to('admin/'.self::$classUrl.'/editar/'.$stdClass->id.'/')
                                                   ->with('mensagemSucesso', $new ? 'Cadastro realizado com sucesso!' : 'Altera&ccedil;&atilde;o realizada com sucesso!');                                  
        endif;        
        
    }

    /**
     * @desc Popula as informações para a View EmailContato\Form, se o mesmo for uma inserção (Cadastro)
     * @return View
     **/    
    public function getCadastrar()
    {
        //Atribui o título da Página à variável $title
        $title = 'Cadastrar '.$this->titulo;        
        //Popula as informações de $title na View Form  
        
        $class = self::$classUrl;
        
        $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/cadastrar', 'name' => 'Cadastrar'),
            );
        
        
        return View::make('admin.'.self::$classUrl.'.form', compact('title', 'class'));
    }
    
    /**
     * @desc Popula as informações para a View Nav\Form, se o mesmo for uma edição (Edição)
     * @param $id
     * @return View
     **/
    public function getEditar($id = '')
    {
        //Atribui o título da Página à variável $title
        $title = 'Editar '.$this->titulo;
        //Atribui a $stdClass a classe especificada na variável estática acima
        $stdClass = self::$class;
        //Atribui a variável $data ao registro buscado na tabela, conforme o código
        $data = $stdClass::findOrFail($id);
        
        $class = self::$classUrl;
        
        $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/editar/'.$id, 'name' => 'Editar'),
            );
        
        $larguraImagemCrop = $this->larguraImagemCrop;
        $alturaImagemCrop = $this->alturaImagemCrop;
        
        //Popula as informações de $title e $data na View Form    
        return View::make('admin.'.self::$classUrl.'.form', compact('title','data', 'class', 'breadcrumbs', 'larguraImagemCrop', 'alturaImagemCrop'));
    }
    
    /**
     * @desc Exclui um EmailContato, conforme o id repassado
     * @param $id
     * @return Redirect
     **/
    public function getExcluir($id = '')
    {
        //Atribui a $stdClass a classe especificada na variável estática acima
        $stdClass = self::$class;
        //Atribui a variável $getProperties ao registro buscado na tabela, conforme o código
        $getProperties = $stdClass::findOrFail($id);
        //Exclui o valor da tabela

        parent::destroyDiretory($this->folder_path.'/'.$getProperties->id);

        $stdClass::destroy($id);
        //Cria log da ação de exclusão

        LogAction::createLog($this->titulo, 'exclusão', $getProperties->pagina.'( '.$id.' )');
        //Redireciona para a View de Listagem, com a mensagem de sucesso
        return Redirect::to('admin/'.self::$classUrl.'/listar')
                                                          ->with('mensagemSucesso','Cadastro exclu&iacute;do com sucesso!');
    }
    
}
