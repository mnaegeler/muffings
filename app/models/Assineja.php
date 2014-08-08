<?php

class Assineja extends BaseModel
{ 
	//Variável padrão para as regras de validação
    public static $rules = array(
        't_nome'       => 'required',
        't_email'      => 'required|email', 
        't_telefone'        => 'required',       
        't_estado'        => 'required',       
        't_cidade'        => 'required',       
        't_info'        => 'required',       
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	't_nome.required'     => 'O campo nome é obrigatório.',
        't_email.required'    => 'O campo e-mail é obrigatório.',    
        't_email.email'       => 'O campo e-mail deve ser um campo de e-mail v&aacute;lido', 
        't_telefone.required'      => 'O campo telefone é obrigatório.',
        't_estado.required'      => 'O campo estado é obrigatório.',
        't_cidade.required'      => 'O campo cidade é obrigatório.',
        't_info.required'      => 'O campo mensagem é obrigatório.',
    );  
     
}
