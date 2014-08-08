<?php

class Imprensa extends BaseModel
 { 
    //Vari�vel padr�o para as regras de valida��o
    public static $rules = array(
        'nome'      => 'required|max:255',
        'data'      => 'required',
        'descricao'      => 'required',
        'status'     => 'required',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
        'nome.required'      => 'O campo título é obrigatório.',
    	'nome.max'      => 'O campo título deve conter no máximo 255 caracteres.',

        'data.required'      => 'O campo data é obrigatório.',
    	'descricao.required'      => 'O campo data é obrigatório.',
        
    	'status.required'     => 'O campo status é obrigatório.', 	
    );

    public static function getData($qtd)
    {
        return DB::table('imprensas')
                                    ->where('status','=',1)
                                    ->paginate($qtd);
    }


}
