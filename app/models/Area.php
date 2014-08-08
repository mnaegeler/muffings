<?php
/**
* PHP version 5
*
* @category   Classe Banner
* @package    Banner (Modelo)
* @copyright  2014 Sou Digital
*/
class Area extends BaseModel { 

    //Vari�vel padr�o para as regras de valida��o
    public static $rules = array(
        'nome'      => 'required',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'nome.required'      => 'O campo nome é obrigatório.',
    );

    public static function getAreaBySlug($slug)
    {
    	return DB::table('areas')->where('slug','=',$slug)->first();
    }

    public static function listagemBannerAdmin()
    {
        return DB::table('banners')
                                    ->join('areas','banners.idArea','=','areas.id')
                                    ->select('areas.nome as nome','areas.id as id')
                                    ->where('banners.status','=',1)
                                    ->groupBy('areas.id')
                                    ->orderBy('banners.ordem')->get();
    }
     
}
