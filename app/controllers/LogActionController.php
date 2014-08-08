<?php
/**
* PHP version 5
*
* @category   Controle de Logs do Usuário
* @package    LogActionController (Controle de Logs do Usuário)
* @copyright  2014 Sou Digital
*/
class LogActionController extends \BaseController
{
    private static $class = 'Log';
    
    private static $classUrl = 'log';
    
    protected $logAction;
    
    private $titulo;

    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o título para uso nas Views
     * @param LogAction $logAction
     * @return void
     **/
    public function __construct(LogAction $logAction)
    {
        parent::__construct();
        $this->logAction = $logAction;
        $this->titulo = 'Log';
    }
    
    /**
     * @desc Lista os 30 últimos registros de ações de usuários e popula os resultados na View
     * @return View
    **/ 
    public function getListar()
    {        
        //Busca os 40 últimos registros da tabela de logs
        $list = LogAction::take(40)->get();
        //Popula a variável para utilização no título da página
        $title = 'Consultar '.$this->titulo;
        $class = self::$classUrl;
        
         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
            );
         
         
        //Popula a View com os resultados da busca dos registros e o título da página
        return View::make('admin.log.listar', compact('list', 'title', 'class', 'breadcrumbs'));
    }      
   
}