<?php
/**
* PHP version 5
*
* @category   Classe BaseModel
* @package    BaseModel (Modelo)
* @copyright  2014 Sou Digital
*/
class BaseModel extends Eloquent 
{
    //Variável setada como false para não criar os campos update_at e insert_at em todas as tabelas
    public $timestamps = false;
    
    /**
     * @desc Método padrão para Validação de todos os modelos que extendem de BaseModel
     * @return void
    **/
    public static function validate($data)
    {
	   return Validator::make($data, static::$rules, static::$messages);
    }
    
}