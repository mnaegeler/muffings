<?php
/**
* PHP version 5
*
* @category   Classe categoriasDocumentos
* @package    categoriasDocumentos (Modelo)
* @copyright  2014 Sou Digital
*/
class CategoriasDocumentos extends BaseModel
{ 
    protected $table = 'categorias_documentos';
    
    public static $rules = array(
        'nome'      => 'required',
        'status'     => 'required',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'nome.required'      => 'O campo nome é obrigatório.',
    	'status.required'     => 'O campo status é obrigatório.', 	
    );
     

     public static function getCategorias()
     {
        return DB::table('categorias_documentos')
                                                ->join('documentos','documentos.idCategoria','=','categorias_documentos.id')
                                                ->where('documentos.status','=',1)
                                                ->where('categorias_documentos.status','=',1)
                                                ->select('categorias_documentos.id as id', 'categorias_documentos.nome as nome','categorias_documentos.slug as slug')
                                                ->get();
     }
}
