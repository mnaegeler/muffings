<?php

class Pergunta extends BaseModel
{ 
	
    public static $rules = array(
        'pergunta'    => 'required|max:255',
        'resposta'     => 'required',   
        'status'  => 'required',
    );

    
    public static $messages = array(
        'pergunta.required' => 'O campo pergunta é obrigatório.',
    	'pergunta.max' => 'O campo pergunta suporta 255 caracteres, caso precise de mais, entre em contato com a Sou Digital.',
    	'resposta.required'    => 'O campo resposta é obrigatório.',    
    	'status.required'    => 'O campo status é obrigatório.', 
    );
     
     public static function getPerguntas($qtd)
     {
        return DB::table('perguntas')->where('status','=',1)->paginate($qtd);
     }
}
