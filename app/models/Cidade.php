<?php
/**
* PHP version 5
*
* @category   Classe Cidade
* @package    Cidade (Modelo)
* @copyright  2014 Sou Digital
*/
class Cidade extends BaseModel 
{ 
    protected $table = 'cidades';

    //Vari�vel padr�o para as regras de valida��o
    public static $rules = array(
        'cidade'      => 'exists:cidades,cidade'
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'cidade.unique'      => 'Não pode haver campos duplicados.'
    );
     
     public static function getCidadesValor($idEstado)
     {
        return DB::table('cidades')
        ->leftjoin('valores','cidades.id','=','valores.idCidade')
        ->select('cidades.id as id','cidades.cidade as cidade','valores.id as idValor','valores.valor as valor','valores.info as info')
        ->where('cidades.idEstado','=',$idEstado)->orderBy('valores.id','DESC')
        ->get();
     }

     public static function getCidades($idEstado)
     {
        return DB::table('cidades')
        ->where('cidades.idEstado','=',$idEstado)->orderBy('cidades.cidade')
        ->get();
     } 
     public static function getCidadesListAdmin($idUnidade)
     {
     	return DB::table('cidades')
        ->join('unidade_cidades','unidade_cidades.idCidade','=','cidades.id')
        ->where('unidade_cidades.idUnidade','=',$idUnidade)
        ->select('cidades.id','cidades.cidade')    
        ->groupBy('cidades.id')
        ->get();
     }

     
}
