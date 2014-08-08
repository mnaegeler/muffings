<?php
/**
* PHP version 5
*
* @category   Controle de EmailContato (Módulos)
* @package    EmailContatoController (Controle de Navs)
* @copyright  2014 Sou Digital
*/
class ComentarioController extends \BaseController
{
    
    private static $class = 'Comentario';
    
    private static $classUrl = 'comentario';
    
    protected $comentario;

    private $titulo;

    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o título para uso nas Views
     * @param comentario $comentario
     * @return void
     **/
    public function __construct(Comentario $comentario)
    {
        if(parent::permissao(self::$classUrl)){
            parent::__construct();
            $this->comentario = $comentario;
            $this->titulo = 'Comentário';
             $this->folder_path = public_path().'/uploads/'.self::$classUrl;

             //Verificar se existe pasta de banners em uploads, sen�o, cria uma
        if (!file_exists($this->folder_path))
            mkdir($this->folder_path, 0777);

        }
        
    }
    
    /**
     * @desc Lista os registros de comentario e popula os resultados na View
     * @return View
     **/
    public function getListar()
    {
        //Busca todos os registros da tabela de EmailContato
        $list = Comentario::getComentarios();
        //Popula a variável para utilização no título da página
        $title = 'Moderar Comentários';
        
        $class = self::$classUrl;
        
        $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
            );
        
        //Popula a View com os resultados da busca dos registros e o título da página
        return View::make('admin.'.self::$classUrl.'.listar', compact('list', 'title', 'class', 'breadcrumbs'));
    }

  
    public function postStore()
    {
        //Busca todos os inputs que foram postados        
        $input = Input::all();
        //Chama o método de validação que consta em models/Nav
        $validator = $this->comentario->validate($input);
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
            
            $stdClass->data = date('Y-m-d');
            $stdClass->save();
        echo 'ok';
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
