<?php
/**
* PHP version 5
*
* @category   Classe Banner
* @package    Banner (Modelo)
* @copyright  2014 Sou Digital
*/
class Blog extends BaseModel { 
    protected $table = 'posts';
   
    public static function getPostHome()
    {
        return DB::table('posts')->where('status','=',1)->orderBy('data', 'DESC')->limit(3)->get();
    }   

    public static function somaAcesso($id)
    {   
        if(!Cookie::get('has-view')):
        	//$cookie = Cookie::make('has-view', 'teste', 30); //Gera por 30 minutos
        	$cookie = Cookie::forever('has-view', 'teste'); //Gera pra sempre
	        $blog = Blog::find($id);
	        $qtde = $blog->qtdeAcesso;
	        $blog->qtdeAcesso = ($qtde) ? $qtde++ : 1;
	        $blog->save();
        endif;	        
    }
    
    public static function getPosts($qtd = 1)
    {
        return DB::table('posts')->where('status','=', 1)->orderby('data', 'DESC')->paginate($qtd);
    }
    
    public static function getPost($slug)
    {
        return Blog::where('slug', '=', $slug)->first();
    }
     
}
