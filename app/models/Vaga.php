<?php

class Vaga extends BaseModel 
{ 
    protected $table = 'vagas';
   
    public static $rules = array(
        'nome'      => 'required|max:65',
        'formacao'      => 'required|max:120',
        'conhecimento'      => 'required',
        'atividade'      => 'required',
        'remuneracao'      => 'required',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'nome.required'      => 'O campo nome é obrigatório.',	
    	'nome.max'      => 'O campo nome tem limite de 65 caracteres.',	

    	'formacao.required'      => 'O campo formação desejável é obrigatório.',	
    	'formacao.max'      => 'O campo formação desejável tem limite de 120 caracteres.',	

    	'conhecimento.required'      => 'O campo conhecimentos fundamentais é obrigatório.',	
    	'atividade.required'      => 'O campo atividades do cargo é obrigatório.',	
    	'remuneracao.required'      => 'O campo remuneração + benefícios é obrigatório.',	
    );

    public static function getVagas()
    {
        return DB::table('vagas')->where('status','=',1)->get();
    }

     public static function getVaga($id)
    {
        return DB::table('vagas')->where('id','=',$id)->first();
    }
}
