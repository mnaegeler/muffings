<?php

class Atendimento extends BaseModel
{ 
	//Variável padrão para as regras de validação
    public static $rules = array(
        'a_nome'       => 'required',
        'a_email'      => 'required|email', 
        'a_info'        => 'required',       
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'a_nome.required'     => 'O campo nome é obrigatório.',
        'a_email.required'    => 'O campo e-mail é obrigatório.',    
        'a_email.email'       => 'O campo e-mail deve ser um campo de e-mail v&aacute;lido', 
        'a_info.required'      => 'O campo mensagem é obrigatório.',
    );  
     
}
