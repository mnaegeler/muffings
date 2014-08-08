<?php
/**
* PHP version 5
*
* @category   Controle geral do Sistema Administrador
* @package    AdminController (Controle do Sistema Administrador)
* @copyright  2014 Sou Digital
*/
class SemPermissaoController extends BaseController
{
    
    /**
     * @desc Popula a index (capa), efetuando a verificação se usuário está logado ou não, enviando para a página correspondente     * 
     * @return View
     **/
    public function getIndex()
    {
        //Se o usuário está autenticado
        if( Auth::user()->check() ): 
            //Popula a View Home (quando usuário estiver logado), baseada na função getHome deste Controller
            $class = '';
            return View::make('admin.sempermissao', compact('title', 'class'));
        else: 
            //Caso contrário, popula a View Login, alterando o título da página
            $title = 'Efetuar Login';
            return View::make('admin.login', compact('title'));            
        endif;
                
    }
    
}