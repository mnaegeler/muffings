<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function() {
	return Redirect::to('/admin');
});


/**
  * @desc Roteamento para a pasta ADMIN, com os controladores espec�ficos
**/

Route::group(array('prefix' => 'admin'), function() {
  
  Route::controllers(array(
   
    // admin/user
    'user' => 'UserController',
    
    
    //admin/acesso
    'acesso'  => 'AcessoController',
    
    //admin/pagina
    'pagina'  => 'PaginaController',
    
    //admin/log
    'log'  => 'LogActionController',

    //admin/email-contato
    'email-contato'  => 'EmailContatoController',

    'basic'  => 'BasicController',

    'sempermissao'  => 'SemPermissaoController',

    'ajax-status/{class}/{id}' => 'StatusController',
      
      
    //admin/banner
    'banner'  => 'BannerController', 

    'localizacao'  => 'LocalizacaoController',

    'area'  => 'AreaController',  

    'unidade'  => 'UnidadeController',  

    'plano'  => 'PlanoController',

    'servico'  => 'ServicoController', 

    'pergunta'  => 'PerguntaController', 

    'categorias-documentos'  => 'CategoriasDocumentosController', 

    'documento'  => 'DocumentosController', 

    'vaga'  => 'VagaController', 

    'comentario'  => 'ComentarioController', 

    'categorias-posts'  => 'CategoriasPostsController', 

    'post'  => 'PostController', 

    'imprensa'  => 'ImprensaController', 

    'press-kit'  => 'PresskitController', 
            
     '/' => 'AdminController',

  ));

});

/* Filtros */
Route::when('admin/*', 'admin');