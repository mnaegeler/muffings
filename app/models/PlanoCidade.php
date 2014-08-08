<?php
/**
* PHP version 5
*
* @category   Classe Localização
* @package    Localização (Modelo)
* @copyright  2014 Sou Digital
*/
class PlanoCidade extends BaseModel { 

	protected $table = 'plano_cidades';
    
    public static function exists($idPlano,$idCidade)
    {
    	$value =  DB::table('plano_cidades')->where('idPlano', '=', $idPlano)->where('idCidade', '=', $idCidade)->first();
    	if($value){
    		return true;
    	}else{
    		return false;
    	}
    }

}
