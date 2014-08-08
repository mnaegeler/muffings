<?php
/**
* PHP version 5
*
* @category   Classe Localização
* @package    Localização (Modelo)
* @copyright  2014 Sou Digital
*/
class UnidadeCidade extends BaseModel { 

	protected $table = 'unidade_cidades';
    
    public static function exists($idUnidade,$idCidade)
    {
    	$value =  DB::table('unidade_cidades')->where('idUnidade', '=', $idUnidade)->where('idCidade', '=', $idCidade)->first();
    	if($value){
    		return true;
    	}else{
    		return false;
    	}
    }

    public static function destroiRelacao($idUnidade)
    {
    	DB::table('unidade_cidades')->where('idUnidade', '=', $idUnidade)->delete();
    }
    
    public static function byUnidade($idUnidade)
     {
        return DB::table('cidades')
                                ->join('unidade_cidades','unidade_cidades.idCidade','=','cidades.id')
                                ->where('unidade_cidades.idUnidade','=',$idUnidade)
                                ->orderBy('unidade_cidades.idUnidade','cidades.cidade')
                                ->get();
     }

}
