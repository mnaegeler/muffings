<?php
/**
* PHP version 5
*
* @category   Classe Acesso
* @package    Acesso (Modelo)
* @copyright  2014 Sou Digital
*/
class Acesso extends BaseModel 
{
    //Variável padrão para as regras de validação
    public static $rules = array();
    
    /**
     * @desc Faz a ligação com a tabela de User, conforme campo user_id da tabela
     * @return User $usuario
    **/ 
    public function user()
    {
       return $this->belongsTo('User', 'user_id');
    } 
    
    /**
     * @desc Método para cadastrar entrada na tabela de Acesso
     * @return void
    **/
    public static function newEntry()
    {
        $acesso = new Acesso();
        //Busca IP do usuário
        $acesso->ip = Request::getClientIp();
        $acesso->data_acesso = date('Y-m-d H:i:s');
        $acesso->user_id = Auth::user()->get()->id;
        //Salva no banco de dados
        $acesso->save();
        
        //Cria sessão para acesso
        Session::put('accessId', $acesso->id);
    }
    
    /**
     * @desc Método para cadastrar saída na tabela de Acesso
     * @return void
    **/
    public static function logoutEntry()
    {
        //Busca os dados do usuário
        $acesso = Acesso::findOrFail(Session::get('accessId'));
        $acesso->data_saida = date('Y-m-d H:i:s');
        //Salva no banco
        $acesso->save();
        
        //Remove Sessão de acesso (accessId)
        Session::flush();
    }
     
}
