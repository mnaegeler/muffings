<?php
/**
* PHP version 5
*
* @category   Classe Pagina
* @package    Pagina (Modelo)
* @copyright  2014 Sou Digital
*/
class Pagina extends BaseModel{
    //Variável padrão para as regras de validação 
    public static $rules = array(
        'pagina'         => 'required|min:3|max:255',
        'title'         => 'required|min:3|max:55',       
        'description'    => 'required|min:3|max:155',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
        'pagina.required'       => 'O campo P&aacute;gina &eacute; requerido',       
        'title.required'       => 'O campo t&iacute;tulo &eacute; requerido', 
        'description.required'  => 'O campo Descri&ccedil;o &eacute; requerido',
    );
    
}