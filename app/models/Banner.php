<?php
/**
* PHP version 5
*
* @category   Classe Banner
* @package    Banner (Modelo)
* @copyright  2014 Sou Digital
*/
class Banner extends BaseModel { 
    //Vari�vel padr�o para as regras de valida��o
    public static $rules = array(
        'nome'      => 'required',
        'descricao'     => 'required',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'nome.required'      => 'O campo nome é obrigatório.',
    	'descricao.required'     => 'O campo descrição é obrigatório.', 	
    );
    
    public static function bannersByOrdem()
    {
        return DB::table('banners')
                                    ->join('areas','banners.idArea','=','areas.id')
                                    ->select('banners.id as id','banners.nome as nome','banners.imagem as imagem','banners.status as status','areas.nome as nomeArea')
        ->orderBy('banners.ordem')->get();
    }


    public static function getBanners($idArea)
    {
        if($idArea) {
             return DB::table('banners')
                                    ->join('areas','banners.idArea','=','areas.id')
                                   
                                    ->where('banners.status','=',1)
                                    ->where('banners.idArea','=',$idArea)
                                    ->select('banners.id as id','banners.nome as nome','banners.imagem as imagem','banners.status as status','banners.url as url' ,'banners.target as target' ,'areas.nome as nomeArea')
                                    ->orderBy('banners.ordem')->get();
        }else{
             return DB::table('banners')
                                    ->join('areas','banners.idArea','=','areas.id')
                                   
                                    ->where('banners.status','=',1)
                                    ->select('banners.id as id','banners.nome as nome','banners.imagem as imagem','banners.status as status','banners.url as url' ,'banners.target as target' ,'areas.nome as nomeArea')
                                    ->orderBy('banners.ordem')->get();
        }
       
    }
     
}
