<?php
/**
* PHP version 5
*
* @category   Classe categoriasDocumentos
* @package    categoriasDocumentos (Modelo)
* @copyright  2014 Sou Digital
*/
class CategoriasPosts extends BaseModel
{ 
    protected $table = 'categorias_posts';
    
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
        return DB::table('categorias_posts')
                                            ->join('posts','categorias_posts.id','=','posts.idCategoria')
                                            ->select('categorias_posts.slug as slug','categorias_posts.nome as nome')
                                            ->where('posts.status','=',1)
                                            ->where('categorias_posts.status','=',1)
                                            ->groupBy('categorias_posts.id')
                                            ->get();

    }
     
}
