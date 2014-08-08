<?php
/**
* PHP version 5
*
* @category   Classe EmailContato
* @package    EmailContato (Modelo)
* @copyright  2014 Sou Digital
*/
class EmailContato extends BaseModel { 
	//Variável padrão para as regras de validação
    public static $rules = array(
        'pagina'    => 'required',
        'email'     => 'required|email',   
        'ccEmail'  => 'email',
        'ccoEmail' => 'email',
    );

    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
    	'pagina.required' => 'O campo P&aacute;gina &eacute; requerido v&aacute;lido',
    	'email.required'    => 'O campo E-mail &eacute; requerido v&aacute;lido',    
    	'email.email'    => 'O campo E-mail dever&aacute; ser um campo de e-mail v&aacute;lido', 
    	'ccEmail.email'    => 'O campo E-mail (CC) dever&aacute; ser um campo de e-mail v&aacute;lido',
    	'ccoEmail.email'    => 'O campo E-mail (CCO) dever&aacute; ser um campo de e-mail v&aacute;lido',
    );
     
}
