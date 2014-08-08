<?php
/**
* PHP version 5
*
* @category   Classe AnalyticsCode
* @package    AnalyticsCode (Modelo)
* @copyright  2014 Sou Digital
*/
class AnalyticsCode extends BaseModel 
{
	//Variável padrão para as regras de validação
	public static $rules = array(
        'codigo' => 'required'
    );     

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'codigo.required' => 'O campo Código Analytics é requerido',    	
    );  
} 