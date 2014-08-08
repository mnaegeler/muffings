<?php
/**
* PHP version 5
*
* @category   Classe AnalyticsCode
* @package    AnalyticsCode (Modelo)
* @copyright  2014 Sou Digital
*/
class Basic extends BaseModel 
{
	protected $table = 'basic_config';
	
	//Variável padrão para as regras de validação
	public static $rules = array(
        'analyticsCode' => 'required'
    );     

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'analyticsCode.required' => 'O campo Código Analytics é obrigatório.',    	
    );  
} 