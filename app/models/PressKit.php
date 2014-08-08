<?php

class PressKit extends BaseModel
{ 
    protected $table = 'presskit';    

    public static $rules = array(
        
        'arquivo' => 'required|mimes:zip'
    
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
   
        'arquivo.mimes'     => 'O campo arquivo obrigatóriamente tem que ser no formato .zip' 
    
    ); 
}
  
