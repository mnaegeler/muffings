<?php
/**
* PHP version 5
*
* @category   Classe LogAction
* @package    LogAction (Modelo)
* @copyright  2014 Sou Digital
*/
class LogAction extends BaseModel 
{ 
    //Variável padrão para as regras de validação
    public static $rules = array();

    //Variável padrão para as mensagens personalizadas
    public static $messages = array();  
    
    /**
     * @desc Faz a ligação com a tabela de User, conforme campo user_id da tabela
     * @return User $usuario
    **/ 
    public function user()
    {
       return $this->belongsTo('User', 'user_id');
    }     
    
    /**
     * @desc Método para cadastrar log na tabela de LogAction
     * @return void
    **/
    public static function createLog($local, $action, $register){
        $log = new LogAction();
        $log->ip        = Request::getClientIp();
        $log->user_id   = Auth::user()->get()->id;
        $log->data_hora = date('Y-m-d H:i:s');
        $log->acao      = Auth::user()->get()->name." (".Auth::user()->get()->username.") efetuou $action em $local - $register";
        $log->save();
    }
    
}