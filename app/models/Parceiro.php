<?php
/**
* PHP version 5
*
* @category   FaleConosco Orcamento
* @package    FaleConosco (Modelo)
* @copyright  2014 Sou Digital
*/
class Parceiro extends BaseModel { 
	//Variável padrão para as regras de validação
    public static $rules = array(
        'p_nome'       => 'required',
        'p_email'      => 'required|email', 
        'p_cidade'     => 'required', 
        'p_estado'     => 'required',
        'p_telefone'        => 'required',       
        'p_info'        => 'required',          
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'p_nome.required'     => 'O campo nome é obrigatório.',

        'p_email.required'    => 'O campo e-mail é obrigatório.', 
        'p_email.email'       => 'O campo e-mail deve ser um e-mail v&aacute;lido', 

        'p_cidade.required'   => 'O campo cidade é obrigatório.',
        'p_estado.required'   => 'O campo estado é obrigatório.',

        'p_telefone.required'      => 'O campo telefone é obrigatório.',
        'p_info.required'      => 'O campo mensagem é obrigatório.',
    );  
     
}
