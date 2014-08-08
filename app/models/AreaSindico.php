<?php

class AreaSindico extends BaseModel 
{ 
	//Variável padrão para as regras de validação
    public static $rules = array(
        's_nome'       => 'required',
        's_email'      => 'required|email', 
        's_telefone'        => 'required',

        /* Condomínio */
           's_condominio'       => 'required',       
           's_cep'       => 'required',       
           's_endereco'       => 'required',       
           's_numero'       => 'required',       
           's_cidade'       => 'required',       
           's_estado'       => 'required',       
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	's_nome.required'     => 'O campo nome é obrigatório.',

        's_email.required'    => 'O campo e-mail é obrigatório.', 
        's_email.email'       => 'O campo e-mail deve ser um campo de e-mail v&aacute;lido', 

        's_telefone.required'      => 'O campo telefone é obrigatório.',

        /* Condomínio */
        's_condominio.required'      => 'O campo condomínio é obrigatório.',
        's_cep.required'      => 'O campo CEP é obrigatório.',
        's_endereco.required'      => 'O campo endereço é obrigatório.',
        's_numero.required'      => 'O campo número é obrigatório.',
        's_cidade.required'      => 'O campo ciddade é obrigatório.',
        's_estado.required'      => 'O campo estado é obrigatório.',
    );  
     
}
