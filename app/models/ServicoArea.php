<?php
/**
* PHP version 5
*
* @category   Classe Localização
* @package    Localização (Modelo)
* @copyright  2014 Sou Digital
*/
class ServicoArea extends BaseModel { 

	protected $table = 'servicos_areas';
    
    public static function exists($idServico,$idArea)
    {
    	$value =  DB::table('servicos_areas')->where('idServico', '=', $idServico)->where('idArea', '=', $idArea)->first();
    	if($value){
    		return true;
    	}else{
    		return false;
    	}
    }

public static function destroiRelacao($idServico)
{
    DB::table('servicos_areas')->where('idServico','=',$idServico)->delete();
}
    
}
