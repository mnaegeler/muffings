<?php

class Servico extends BaseModel 
{ 
    //Vari�vel padr�o para as regras de valida��o
    public static $rules = array(
        'nome'      => 'required',
        'descricao'      => 'required',
        'arquivo' => 'mimes:pdf,',
        'arquivo2' => 'mimes:pdf,zip'
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'nome.required'      => 'O campo nome é obrigatório.',	
        'descricao.required'      => 'O campo descrição é obrigatório.',    
        'arquivo.mimes'      => 'O campo arquivo tem que ser no formato PDF.',  
    	'arquivo2.mimes'      => 'O campo arquivo tem que ser no formato PDF ou ZIP.',	
    );

    public static function getServicos($idArea = null)
    {
        if($idArea){
            return DB::table('servicos')
                                        ->join('servicos_areas','servicos.id','=','servicos_areas.idServico')
                                        ->leftjoin('planos','planos.idServico','=','servicos.id')
                                        ->select('servicos.id as id','servicos.nome as nome', 'servicos.chamada as chamada','servicos.imagem as imagem', 'servicos.slug as slug','planos.id as existServico')
                                        ->groupBy('servicos.id')
            ->where('servicos_areas.idArea','=',$idArea)
            ->orderBy('servicos.nome')
            ->get();
        }
    }

    public static function getServico($slug, $idArea = null)
    {
        if($idArea){

          return DB::table('servicos')
                                    ->join('servicos_areas','servicos_areas.idServico','=','servicos.id')
                                    ->where('servicos.slug','=', $slug)
                                    ->where('servicos_areas.idArea','=', $idArea)
                                    ->select('servicos.id as id',
                                        'servicos.slug as slug',
                                        'servicos.nome as nome',
                                        'servicos.descricao as descricao',
                                        'servicos.arquivo as arquivo',
                                        'servicos.arquivo2 as arquivo2', 
                                        'servicos.imagem as imagem',
                                        'servicos.chamada as chamada')
                                    ->first();
        }else{
            return DB::table('servicos')->where('slug','=', $slug)->first();
        }
    }

    public static function getServicoById($id)
    {
          return DB::table('servicos')->where('id','=', $id)->first();
    }

    public static function slug($title)
    {
        $slug = Str::slug($title);
        $slugCount = count( Servico::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get() );

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }


}