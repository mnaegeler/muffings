<?php
/**
* PHP version 5
*
* @category   Classe Banner
* @package    Banner (Modelo)
* @copyright  2014 Sou Digital
*/
class Estado extends BaseModel { 

    //Vari�vel padr�o para as regras de valida��o
    public static $rules = array(
        'nome'      => 'required|max:65',
        'sigla'      => 'required|max:10',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
        'nome.required'      => 'O campo nome é obrigatório.',
        'nome.max'      => 'O campo nome deve conter no máximo 65 caracteres.', 

        'sigla.required'      => 'O campo sigla é obrigatório.',
    	'sigla.max'      => 'O campo sigla deve conter no máximo 10 caracteres.',
    );
     
     public static function getEstados()
     {
     	 $estados =  DB::table('estados')
     						->join('cidades','estados.id','=','cidades.idEstado')
     						->join('unidade_cidades','unidade_cidades.idCidade','=','cidades.id')
     						->select('estados.nome' , 'estados.id')
     						->groupBy('estados.id')
     						->get();

     	foreach($estados as $estado){
     		$arr = array($estado->id => $estado->nome);
     	}
     	return $arr;
     }

     public static function getListAll()
     {
       return DB::table('estados')->get();
     }
}
