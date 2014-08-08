<?php
/**
* PHP version 5
*
* @category   Controle de Acesso
* @package    AcessoController (Controle de Acesso)
* @copyright  2014 Sou Digital
*/
class AcessoController extends \BaseController
{
    private static $class = 'Acesso';
    
    private static $classUrl = 'acesso';
    
    protected $acesso;
    
    private $titulo;

    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o título para uso nas Views
     * @param Acesso $acesso
     * @return void
     **/
    public function __construct(Acesso $acesso)
    {
        parent::__construct();
        $this->acesso = $acesso;
        $this->titulo = 'Acessos';

        if(!parent::permissao(self::$classUrl))
                return Redirect::to('admin/sempermissao');
            
    }

    /**
     * @desc Lista os 30 últimos registros de entrada e saída do sistema e popula os resultados na View
     * @return View
     **/    
    public function getListar()
    {
        if(!parent::permissao(self::$classUrl))
                return Redirect::to('admin/sempermissao');
        
        //Busca os 30 últimos registros da tabela de acessos
        $list = Acesso::take(30)->get();
        //Popula a variável para utilização no título da página
        $title = 'Consultar '.$this->titulo;
        $class = self::$classUrl;
        
         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
            );
         
         
        //Popula a View com os resultados da busca dos registros e o título da página
        return View::make('admin.acesso.listar', compact('list', 'title','class', 'breadcrumbs'));
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
        foreach ($ids as $id){
                //Atribui a $stdClass a classe especificada na vari�vel est�tica acima
            $stdClass = self::$class;
            //Atribui a vari�vel $getProperties ao registro buscado na tabela, conforme o c�digo
            $getProperties = $stdClass::findOrFail($id); 

            //Exclui o valor da tabela       
            $stdClass::destroy($id);
            //Cria log da a��o de exclus�o
            LogAction::createLog($this->titulo, 'exclusão', $getProperties->id.'( '.$id.' )');
            //Redireciona para a View de Listagem, com a mensagem de sucesso
        }
        
        
        return Redirect::to('admin/'.self::$classUrl.'/listar')
                                                ->with('mensagemSucesso',($countIds > 1) ? "Itens excluídos com sucesso!": "Item excluido com sucesso!");
    } 
   
}