<?php
/**
* PHP version 5
*
* @category   Classe Post
* @package    Post (Modelo)
* @copyright  2014 Sou Digital
*/
class Post extends BaseModel { 
    //Vari�vel padr�o para as regras de valida��o
    public static $rules = array(
        'nome'      => 'required|max:255',
        'data'      => 'required',
       
        'idCategoria'      => 'required',
        'status'     => 'required',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
        'nome.required'      => 'O campo título é obrigatório.',
    	'nome.max'      => 'O campo título deve conter no máximo 255 caracteres.',

        'data.required'      => 'O campo data é obrigatório.',
       

    	'idCategoria.required'      => 'O campo data é obrigatório.',
        
    	'status.required'     => 'O campo status é obrigatório.', 	
    );

    public static function getOrderByView()
    {
        return DB::table('posts')->where('status','=','1')->orderBy('qtdeAcesso','DESC')->orderBy('data','DESC')->limit(3)->get();
    }

    public static function getPosts($qtd,$idCategoria = null)
    {
        if($idCategoria){
             return DB::table('posts')
                                ->join('categorias_posts','categorias_posts.id','=','posts.idCategoria')
                                ->select('posts.id as id','posts.nome as nome','posts.data as data','posts.descricao as descricao','posts.slug as slug', 'posts.imagem as imagem',
                                    'categorias_posts.nome as categoria')
                                ->where('posts.status','=',1)
                                ->where('categorias_posts.status','=',1)
                                ->where('categorias_posts.id','=',$idCategoria)
                                ->orderBy('data','DESC')
                                ->paginate($qtd);
        }else{
             return DB::table('posts')
                                ->join('categorias_posts','categorias_posts.id','=','posts.idCategoria')
                                ->select('posts.id as id','posts.nome as nome','posts.data as data','posts.descricao as descricao','posts.slug as slug', 'posts.imagem as imagem','categorias_posts.nome as categoria')
                                ->where('posts.status','=',1)
                                ->where('categorias_posts.status','=',1)
                                ->orderBy('data','DESC')
                                ->paginate($qtd);
        }

    }

    public static function getMoreViews()
    {
        return DB::table('posts')
                                ->join('categorias_posts','categorias_posts.id','=','posts.idCategoria')
                                ->select('posts.id as id','posts.nome as nome','posts.data as data','posts.descricao as descricao','posts.slug as slug', 'posts.imagem as imagem','categorias_posts.nome as categoria')
                                ->where('posts.status','=',1)
                                ->where('categorias_posts.status','=',1)
                                ->orderBy('countAcesso','DESC','data','DESC')
                                ->limit(3)
                                ->get();
    }   

     public static function getHome()
    {
        return DB::table('posts')
                                ->join('categorias_posts','categorias_posts.id','=','posts.idCategoria')
                                ->select('posts.id as id','posts.nome as nome','posts.data as data','posts.descricao as descricao','posts.slug as slug', 'posts.imagem as imagem','categorias_posts.nome as categoria')
                                ->where('posts.status','=',1)
                                ->where('categorias_posts.status','=',1)
                                ->orderBy('countAcesso','DESC','data','DESC')
                                ->limit(4)
                                ->get();
    }

    public static function getPost($slug)
    {
       return DB::table('posts')
                                ->join('categorias_posts','categorias_posts.id','=','posts.idCategoria')
                                ->select('posts.id as id','posts.nome as nome','posts.data as data','posts.descricao as descricao','posts.slug as slug', 'posts.imagem as imagem','categorias_posts.nome as categoria', 'categorias_posts.slug as slugCategoria')
                                ->where('posts.status','=',1)
                                ->where('categorias_posts.status','=',1)
                                ->where('posts.slug','=',$slug)
                                ->first(); 
    }

public static function somaAcesso($id)
    {   
        if(!Cookie::get('has-view')):
            //$cookie = Cookie::make('has-view', 'teste', 30); //Gera por 30 minutos
            $cookie = Cookie::forever('has-view', 'teste'); //Gera pra sempre
            $blog = Post::find($id);
            $qtde = $blog->countAcesso;
            $blog->countAcesso = ($qtde) ? $qtde++ : 1;
            $blog->save();
        endif;          
    }

}
