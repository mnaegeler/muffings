<?php
/**
* PHP version 5
*
* @category   FaleConosco Orcamento
* @package    FaleConosco (Modelo)
* @copyright  2014 Sou Digital
*/
class TrabalheConosco extends BaseModel { 
	//Variável padrão para as regras de validação
    public static $rules = array(
        't_nome'       => 'required',
        't_email'      => 'required|email', 
        't_cidade'     => 'required', 
        't_estado'     => 'required',
        't_vagas'        => 'required',       
        't_anexo'        => 'required|mimes:doc,docx,pdf',       
        't_info'        => 'required',       
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	't_nome.required'     => 'O campo nome é obrigatório.',
        't_email.required'    => 'O campo e-mail é obrigatório.',    
        't_email.email'       => 'O campo e-mail deve ser um campo de e-mail v&aacute;lido', 
        't_cidade.required'   => 'O campo cidade é obrigatório.',
        't_estado.required'   => 'O campo estado é obrigatório.',
        't_vagas.required'      => 'O campo vaga é obrigatório.',
        't_anexo.required'      => 'O campo anexo é obrigatório.',
        't_anexo.mimes'      => 'O campo anexo deve conter somente arquivos DOC ou PDF.',
        't_info.required'      => 'O campo mensagem é obrigatório.',
    );  
     
}
