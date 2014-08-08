<?php

class Plano extends BaseModel 
{ 
    //Vari�vel padr�o para as regras de valida��o
    public static $rules = array(
        'nome'      => 'required|max:65',
        'descricao'      => 'required|max:160',
        'idServico'      => 'required',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
        'nome.required'      => 'O campo nome é obrigatório.',  
        'descricao.max'      => 'O campo nome deve conter no máximo 65 caracteres.',  

        'descricao.required'      => 'O campo nome é obrigatório.', 
    	'descricao.max'      => 'O campo descrição deve conter no máximo 160 caracteres.',	

    	'idServico.required'      => 'O campo serviço é obrigatório.',	
    );

    public static function getPlanosByArea()
    {
    	return DB::table('planos')
    						->join('servicos', 'planos.idServico','=','servicos.id')
    						->select('planos.id as id', 'planos.nome as nome','planos.obs as obs','planos.status as status',  'servicos.nome as servico')
                            ->orderBy('planos.ordem','ASC')
    						->get();
    }

public static function getPlanos($idServico,$idCidade,$idArea)
{

    return DB::table('planos')
                        ->join('servicos','servicos.id','=','planos.idServico')
                        ->join('valores','valores.idPlano','=','planos.id')
                        ->where('valores.idCidade','=',$idCidade)
                        ->where('planos.idServico','=',$idServico)
                        ->where('planos.idArea','=',$idArea)
                        ->where('planos.status','=',1)
                        ->select('planos.id','planos.nome as nome','planos.idCor as idCor','valores.valor as valor','valores.formato as formato','valores.info as info','planos.descricao as descricao')
                        ->orderBy('planos.ordem',"ASC")
                        ->get();
}



    public static function destroiRelacao($idPlano)
    {
        DB::table('valores')->where('idPlano', '=', $idPlano)->delete();
    }
    

}
