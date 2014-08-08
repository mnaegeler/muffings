<?php
/**
* PHP version 5
*
* @category   Controle de Páginas para SEO (Módulos)
* @package    PaginaController (Controle de Páginas para SEO )
* @copyright  2014 Sou Digital
*/
class PaginaController extends \BaseController
{
    
    private static $class = 'Pagina';
    
    private static $classUrl = 'pagina';
    
    protected $pagina;

    private $titulo;

    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o título para uso nas Views
     * @param Pagina $pagina
     * @return void
    **/ 
    public function __construct(Pagina $pagina)
    {
        parent::__construct();
        $this->pagina = $pagina;
        $this->titulo = 'P&aacute;ginas';
    }
    
    /**
     * @desc Lista os registros de Paginas e popula os resultados na View
     * @return View
    **/
    public function getListar()
    {            
        //Busca todos os registros da tabela de Paginas   
        $list = Pagina::all();
        //Popula a variável para utilização no título da página
        $title = 'Consultar '.$this->titulo;
        
        $class = self::$classUrl;
        
        $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
            );
        
        
        //Popula a View com os resultados da busca dos registros e o título da página
        return View::make('admin.pagina.listar', compact('list', 'title', 'class', 'breadcrumbs'));
    }

    /**
     * @desc Efetua a inserção/alteração do registro
     * @return Redirect
    **/
    public function postStore()
    {       
        //Busca todos os inputs que foram postados          
        $input = Input::all();
        //Chama o método de validação que consta em models/Pagina
        $validator = $this->pagina->validate($input);
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
                if( $key != '_token' ):
                    //Atribui o valor do campo da base de dados ao valor do input
                    $stdClass->$key = $data;
                endif; 
            endforeach;
            //Salva no banco de dados as informações
            $stdClass->save();

            //Verifica se é uma inserção ou alteração e cria um registro na tabela de Logs, informando o título da página, código gerado e input com as informações
            $log = ($new) ? LogAction::createLog($this->titulo, 'inserção', $input['pagina'].'( '.$stdClass->id.' )') : LogAction::createLog($this->titulo, 'edição', $input['pagina'].' ( '.$stdClass->id.' )');

            //Redireciona para a listagem com a mensagem de sucesso, conforme o tipo de operação
            return Redirect::to('admin/pagina/editar/'.$stdClass->id.'/')
                                                   ->with('mensagemSucesso', $new ? 'Cadastro realizado com sucesso!' : 'Altera&ccedil;&atilde;o realizada com sucesso!');                                  
        endif;        
        
    }
    
    /**
     * @desc Popula as informações para a View Pagina\Form, se o mesmo for uma inserção (Cadastro)
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
        
        
        return View::make('admin.pagina.form', compact('title', 'class', 'breadcrumbs'));
    }
    
    /**
     * @desc Popula as informações para a View Pagina\Form, se o mesmo for uma edição (Edição)
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
        
        
        //Popula as informações de $title e $data na View Form    
        return View::make('admin.pagina.form', compact('title','data', 'class', 'breadcrumbs'));
    }
    
    /**
     * @desc Exclui uma Pagina, conforme o id repassado
     * @param $id
     * @return Redirect
     **/
    public function getExcluir($id = '') {
        $ids = parent::prepareIds($id);
        $countIds = count($ids);
        foreach ($ids as $id) {
            //Atribui a $stdClass a classe especificada na variável estática acima
            $stdClass = self::$class;
            //Atribui a variável $getProperties ao registro buscado na tabela, conforme o código
            $getProperties = $stdClass::findOrFail($id);
            //Exclui o valor da tabela
            $stdClass::destroy($id);
            //Cria log da ação de exclusão
            LogAction::createLog($this->titulo, 'exclusão', $getProperties->pagina . '( ' . $id . ' )');
            //Redireciona para a View de Listagem, com a mensagem de sucesso
        }

        return Redirect::to('admin/' . self::$classUrl . '/listar')
                        ->with('mensagemSucesso', ($countIds > 1) ? "Itens excluídos com sucesso!" : "Item excluido com sucesso!");
    }

}