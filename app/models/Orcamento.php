<?php
/**
* PHP version 5
*
* @category   Classe Orcamento
* @package    Orcamento (Modelo)
* @copyright  2014 Sou Digital
*/
class Orcamento extends BaseModel { 
	//Variável padrão para as regras de validação
    public static $rules = array(
        'nome'       => 'required',
        'email'      => 'required|email', 
        'telefone'   => 'required', 
        'empresa'    => 'required', 
        'cidade'     => 'required', 
        'estado'     => 'required',
        'msg'        => 'required',       
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'nome.required'     => 'O campo Nome &eacute; requerido',
        'email.required'    => 'O campo E-mail &eacute; requerido requerido',    
        'email.email'       => 'O campo E-mail dever&aacute; ser um campo de e-mail v&aacute;lido', 
        'telefone.required' => 'O campo Telefone &eacute; requerido',
        'empresa.required'  => 'O campo Empresa &eacute; requerido',
        'cidade.required'   => 'O campo Cidade &eacute; requerido',
        'estado.required'   => 'O campo Cidade &eacute; requerido',
        'msg.required'      => 'O campo Mensagem &eacute; requerido',
    );  
     
}
