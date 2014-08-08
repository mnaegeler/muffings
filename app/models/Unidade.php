<?php
/**
* PHP version 5
*
* @category   Classe Localização
* @package    Localização (Modelo)
* @copyright  2014 Sou Digital
*/
class Unidade extends BaseModel { 
    //Vari�vel padr�o para as regras de valida��o
    public static $rules = array(
        'nome'      => 'required|max:65',
        'endereco'      => 'required|max:160',
        'bairro'      => 'required|max:65',
        'email'      => 'required|max:120',
        'telefone'      => 'required|max:120',
        'atendimento'      => 'required|max:255',
        'latitude'      => 'required',
        'longitude'      => 'required',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'nome.required'      => 'O campo nome é obrigatório.',
    	'endereco.max'      => 'O campo nome deve conter no máximo 65 caracteres.',

    	'endereco.required'      => 'O campo endereço é obrigatório.',
    	'endereco.max'      => 'O campo endereço deve conter no máximo 160 caracteres.',

    	'bairro.required'      => 'O campo bairro é obrigatório.',
    	'bairro.max'      => 'O campo bairro deve conter no máximo 65 caracteres.',	

    	'email.required'      => 'O campo e-mail é obrigatório.',
    	'email.max'      => 'O campo e-mail deve conter no máximo 120 caracteres.',

		'telefone.required'      => 'O campo telefone é obrigatório.',
    	'telefone.max'      => 'O campo telefone deve conter no máximo 120 caracteres.',	

		'atendimento.required'      => 'O campo atendimento é obrigatório.',
    	'atendimento.max'      => 'O campo atendimento deve conter no máximo 255 caracteres.',	
        
        'latitude.required'      => 'O campo latitude é obrigatório.',
        'longitude.required'      => 'O campo longitude é obrigatório.',

    );
    	

        public static function getUnidadeByCidade($idCidade)
        {
            return DB::table('unidades')
                                        ->join('unidade_cidades','unidade_cidades.idUnidade','=','unidades.id')
                                        ->join('cidades','cidades.id','=','unidade_cidades.idCidade')
                                        ->join('estados','estados.id','=','cidades.idEstado')
                                        ->select('unidades.nome as nome','unidades.telefone as telefone','cidades.cidade as cidade','estados.sigla as sigla')
                                        ->where('cidades.id','=',$idCidade)
                                        ->orderBy('unidades.nome')
                                        ->groupBy('unidades.id')
                                        ->first();
        }

        public static function getAll()
        {
            return DB::table('unidades')
                                        ->orderBy('unidades.nome')
                                        ->get();
        }
    
}
