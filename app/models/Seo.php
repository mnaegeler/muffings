<?php

class Seo extends BaseModel 
{ 
    protected $table = 'paginas';

     public static function prepareUrl()
     {
        return str_replace(url(), '', URL::current());
     }

     public static function getSeo($page)
     {
        if($page){
            return DB::table('paginas')->where('pagina', $page)->first();
        }else{
            if(self::prepareUrl() == ''){
                return DB::table('paginas')->where('pagina','/home')->first();
            }else{
                return DB::table('paginas')->where('pagina',self::prepareUrl())->first();
            }
        }
     }

     public static function dataOfSeo($page = '',$titulo = '')
     {
        $seo = self::getSeo($page);
        $basicConfig = self::getInfoBasic();
        if(!$seo){
            if(!$titulo){
                $objectReturn = new stdClass();
                $objectReturn->title = $basicConfig->nomeSite;
                $objectReturn->description = '';
            }else{
                $objectReturn = new stdClass();
                $objectReturn->title = $titulo . ' > '.$basicConfig->nomeSite;
                $objectReturn->description = $titulo;
            }
            return $objectReturn;
        }else{
            return $seo;
        }

     }

     public static function getInfoBasic()
     {
        $basic = DB::table('basic_config')->where('id',1)->first();
        $objectReturn = new stdClass();
        $objectReturn->analyticsCode = $basic->codigoAnalytics;
        $objectReturn->nomeSite = $basic->nomeSite;
        if( self::prepareUrl() == ''){
            $objectReturn->analyticsTitle = '/home';
        }else{
            $objectReturn->analyticsTitle = self::prepareUrl();
        }
        
        return $objectReturn;
     }
}
