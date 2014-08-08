<?php

class Valor extends BaseModel 
{ 
    protected $table = 'valores';
    
   public static function getValor($idPlano,$idCidade)
   {
        return DB::table('valores')->where('idPlano','=',$idPlano)->where('idCidade','=',$idCidade)->first();
   }
}
