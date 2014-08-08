<?php
/**
* PHP version 5
*
* @category   Classe Localização
* @package    Localização (Modelo)
* @copyright  2014 Sou Digital
*/
class Localizacao extends BaseModel { 
    //Vari�vel padr�o para as regras de valida��o
    public static $rules = array(
        'nome'      => 'required',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'nome.required'      => 'O campo nome é obrigatório.',	
    );
    
    public static function getEstados()
    {
        return DB::table('estado')->get();
    }
     
}
