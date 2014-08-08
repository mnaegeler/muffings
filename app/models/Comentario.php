<?php

class Comentario extends BaseModel
{ 
     protected $table = 'comentarios';

     public static $rules = array(
        'nome'      => 'required',
        'email'     => 'required',
        'comentario'     => 'required',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
     'nome.required'      => 'O campo nome é obrigatório.',
     'email.required'     => 'O campo e-mail é obrigatório.',  
     'comentario.required'     => 'O campo comentário é obrigatório.',  
    );

     public static function getComentarios($idPost = null)
     {
     	if($idPost){
     		return DB::table('comentarios')	
     										->join('posts', 'posts.id', '=', 'comentarios.idPost')
     										->where('comentarios.idPost' ,'=',$idPost)
     										->where('comentarios.status' ,'=',1)
                                                       ->select('comentarios.id as id','comentarios.nome as nome','comentarios.data as data','comentarios.status as status','comentarios.comentario as comentario','posts.nome as nomePost')
     										->get();
     	}else{
     			return DB::table('comentarios')	
     										->join('posts', 'posts.id', '=', 'comentarios.idPost')
     										->select('comentarios.id as id','comentarios.nome as nome','comentarios.status as status','comentarios.comentario as comentario','posts.nome as nomePost')
     										->get();
     	}
     }

}
