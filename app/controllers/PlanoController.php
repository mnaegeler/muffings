<?php
/**
* PHP version 5
*
* @category   Controle de Banners do Admin (M�dulos)
* @package    BannerController (Controle de Banners do Admin)
* @copyright  2014 Sou Digital
*/

class PlanoController extends \BaseController
{  
    private static $class = 'Plano';
    
    private static $classUrl = 'plano';
    
    protected $plano;
    
    private $storeNav;
    
    private $folder_path;
    
    private $titulo;
    
    
    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o t�tulo para uso nas Views
     * @param plano $plano
     * @return void
    **/ 
    public function __construct(Plano $plano)
    {       
        parent::__construct();
         
        $this->plano = $plano;

        $this->storeNav = array();  
        //Atributos para pasta de Uploads
        $this->folder_path = public_path().'/uploads/'.self::$classUrl;
        $this->titulo = 'plano';
        
        //Verificar se existe pasta de banners em uploads, sen�o, cria uma
        if (!file_exists($this->folder_path))
            mkdir($this->folder_path, 0777);
    }
    
    /**
     * @desc Lista os registros de Banners e popula os resultados na View
     * @return View
    **/
    public function getListar()
    {
            if(!parent::permissao(self::$classUrl)) 
                return Redirect::to('admin/sempermissao');
            
             //Busca todos os registros da tabela de Banners   
            $list = Plano::getPlanosByArea();
            //Popula a vari�vel para utiliza��o no t�tulo da p�gina      
            $title = 'Consultar Planos';

            $class = self::$classUrl;
            
            $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
            );
            
            //Popula a View com os resultados da busca dos registros e o t�tulo da p�gina
            return View::make('admin.'.self::$classUrl.'.listar', compact('list', 'title', 'class', 'breadcrumbs'));
       
       
    }
    
    public function getCadastrar()
    {
       if(!parent::permissao(self::$classUrl)) 
                return Redirect::to('admin/sempermissao');
        
        
        //Atribui o t�tulo da P�gina � vari�vel $title
        $title = 'Cadastrar Plano';

        $servicos = Servico::lists('nome','id');

        $areas = Area::where('id','<>', 2)->lists('nome','id');
        
        $estados = Estado::all();


        
        $class = self::$classUrl;
        
         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/cadastrar', 'name' => 'Cadastrar Plano'),
            );
         
        
        return View::make('admin.'.self::$classUrl.'.cadastrar', compact('title','class', 'breadcrumbs', 'servicos', 'estados', 'areas'));
    }   
    
    /**
     * @desc Popula as informa��es para a View Banner\Form, se o mesmo for uma edi��o (Edi��o)
     * @param $id
     * @return View
    **/
    public function getEditar($id = '')
    {   
        
        if(!parent::permissao(self::$classUrl)) 
                return Redirect::to('admin/sempermissao');
        
        $title = 'Editar '.$this->titulo;    
        
        $estados = Estado::all();
        $plano = Plano::find($id);
        $class = self::$classUrl;
        $servicos = Servico::lists('nome','id');
        $areas = Area::where('id','<>', 2)->lists('nome','id');
        
         $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/editar/'.$id, 'name' => 'Editar'),
            );
         
        
        return View::make('admin.'.self::$classUrl.'.alterar', compact('title', 'class', 'breadcrumbs','plano', 'estados','servicos','areas'));
    }
        
  
    public function postCadastrar()
    {   
        //Busca todos os inputs que foram postados     
        $input = Input::all();
        //Chama o m�todo de valida��o que consta em models/Pagina
        $validator = Plano::validate($input);
       
        if( $validator->fails() ):
            //Redireciona para a View de Cadastro com as mensagens de erros
            return Redirect::back()
                                  ->withInput()
                                  ->withErrors($validator);
        else:
             $plano = new Plano();
             $plano->nome = $input['nome'];
             $plano->idServico = $input['idServico'];
             $plano->idCor = $input['idCor'];
             $plano->obs = $input['obs'];
             $plano->status = $input['status'];
             $plano->descricao = $input['descricao'];
             $plano->idArea = $input['idArea'];
             $plano->save();


             LogAction::createLog($this->titulo, 'inserção', $input['nome'].'( '.$plano->id.' )');
            
                 return Redirect::to('admin/'.self::$classUrl.'/editar/'.$plano->id.'/')
                                                   ->with('mensagemSucesso', 'Cadastro realizado com sucesso.');     
            
                                        
        endif;        
        
    }


    public function postAlterar()
    {
        $input = Input::all();

        $validator = Plano::validate($input);

        if( $validator->fails() ):
            //Redireciona para a View de Cadastro com as mensagens de erros
            return Redirect::back()
                                  ->withInput()
                                  ->withErrors($validator);
        else:
             $plano =  Plano::find($input['id']);

             $plano->nome = $input['nome'];
             $plano->idServico = $input['idServico'];
             $plano->idCor = $input['idCor'];
             $plano->obs = $input['obs'];
             $plano->status = $input['status'];
             $plano->descricao = $input['descricao'];
             $plano->idArea = $input['idArea'];
             $plano->save();

             foreach(Estado::all() as $estado)
             {
                 $cidades = Cidade::getCidades($estado->id);
                 
                Plano::destroiRelacao($input['id']);

             foreach ($cidades as $cidade){
                
                 if(isset($input['preco-'.$cidade->id])  && isset($input['forma-'.$cidade->id])){

                    if($input['preco-'.$cidade->id] != ''  && $input['forma-'.$cidade->id] != ''){
                         $valor = new Valor();

                       $valor->idCidade = $cidade->id;
                        $valor->idPlano = $plano->id;
                        
                        
                        $valor->valor =  $input['preco-'.$cidade->id];
                        $valor->formato = $input['forma-'.$cidade->id];                
                        if(isset($input['info-'.$cidade->id])){
                            $valor->info = $input['info-'.$cidade->id];
                        }
                             
                       $valor->save();
                    }
                   

                 }
             }
             }
            


             LogAction::createLog($this->titulo, 'Alterar', $input['nome'].'( '.$plano->id.' )');
            
                 return Redirect::to('admin/'.self::$classUrl.'/editar/'.$plano->id.'/')
                                                   ->with('mensagemSucesso', 'Alteração efetuada com sucesso.');     
                                      
        endif;        
    }


    public function getExcluir($id = '')
    {
        if(!parent::permissao(self::$classUrl)) 
                return Redirect::to('admin/sempermissao');

        $ids = parent::prepareIds($id);
        
        $countIds = count($ids);
        foreach ($ids as $id){
            $getProperties = Plano::findOrFail($id); 
              
            Plano::destroy($id);

            LogAction::createLog($this->titulo, 'exclusão', $getProperties->nome.'( '.$id.' )');
        }
        
        
        return Redirect::to('admin/'.self::$classUrl.'/listar')
                                                ->with('mensagemSucesso',($countIds > 1) ? "Itens excluídos com sucesso!": "Item excluido com sucesso!");
    } 


    public function postExcluirValor()
    {
        $input = Input::all();
        Valor::destroy($input['idValor']);
    }


     public function getOrdenar($ids)
    {
        $ids = parent::prepareIds($ids);
       
        $i =1;
        foreach ($ids as $id){
            $class = Plano::findOrFail($id);
            $class->ordem = $i;
            $class->save();
            $i++;
        }
    }

}

