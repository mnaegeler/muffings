<?php
/**
* PHP version 5
*
* @category   FaleConosco Orcamento
* @package    FaleConosco (Modelo)
* @copyright  2014 Sou Digital
*/
class LigamosPraVoce extends BaseModel { 
	//Variável padrão para as regras de validação
    public static $rules = array(
        'l_nome'       => 'required',
        'l_telefone'   => 'required', 
        'l_horario'     => 'required',    
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'l_nome.required'     => 'O campo nome é obrigatório.',
        'l_telefone.required'    => 'O campo telefone é obrigatório.',    
        'l_horario.required'    => 'O campo melhor horário é obrigatório.',    
    );  
     
}
