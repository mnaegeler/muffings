<?php
/**
* PHP version 5
*
* @category   Classe categoriasDocumentos
* @package    categoriasDocumentos (Modelo)
* @copyright  2014 Sou Digital
*/
class Documentos extends BaseModel
{ 
    protected $table = 'documentos';
    
    public static $rules = array(
        'nome'      => 'required',
        'idCategoria'     => 'required',
        'arquivo' => 'required|mimes:pdf,zip',
        'status'     => 'required',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'nome.required'      => 'O campo nome é obrigatório.',
        'idCategoria.required'     => 'O campo categoria é obrigatório.',   
        'arquivo.required'     => 'O campo arquivo é obrigatório.',   
        'arquivo.mimes'     => 'O campo arquivo obrigatóriamente tem que ser no formato .pdf ou .zip',   
    	'status.required'     => 'O campo status é obrigatório.', 	
    );

     
    public static function getDocumentos($idCategoria,$status = null) 
    {
        if(!$status)
            return DB::table('documentos')->where('idCategoria','=',$idCategoria)->where('status','=',1)->get();
        else
            return DB::table('documentos')->where('idCategoria','=',$idCategoria)->get();

    }
}
