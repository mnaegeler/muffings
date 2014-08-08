<?php
/**
* PHP version 5
*
* @category   Controle de Usuários do Admin (Módulos)
* @package    UserController (Controle de Usuários do Admin)
* @copyright  2014 Sou Digital
 * 
*/
use Intervention\Image\Image;

class UserController extends \BaseController
{  
    private static $class = 'User';
    
    private static $classUrl = 'user';
    
    protected $usuario;
    
    private $storeNav;
    
    private $folder_path;
    
    private $titulo;

    private $thumbs = array(
        0 => array('largura' => 38, 'altura' => 38),
        1 => array('largura' => 150, 'altura' => 150),
    ); 
    
    private $alturaImagemCrop = 400;
    
    private $larguraImagemCrop = 400;
    
    private $alturaImagemExibicao = 150;
    
    private $larguraImagemExibicao = 150;
    
    
    
    
    /**
     * @desc Construtor da Classe, retornando os atributos herdados e o título para uso nas Views
     * @param User $usuario
     * @return void
    **/ 
    public function __construct(User $usuario)
    {
        parent::__construct();
        $this->usuario = $usuario;
        $this->storeNav = array();  
        //Atributos para pasta de Uploads
        $this->folder_path = public_path().'/uploads/users';
        $this->titulo = 'Usu&aacute;rios';
        
        //Verificar se existe pasta de usuários em uploads, senão, cria uma
        if (!file_exists($this->folder_path))
            mkdir($this->folder_path, 0777);
        
    }
    
    /**
     * @desc Lista os registros de Usuários e popula os resultados na View
     * @return View
    **/
    public function getListar()
    {
        
        if(!parent::permissao(self::$classUrl))
                return Redirect::to('admin/sempermissao');
        
        
        //Busca todos os registros da tabela de Usuários   
        $list = User::getUsers(); 
        //Popula a variável para utilização no título da página      
        $title = 'Consultar Usu&aacute;rio';
        $class = self::$classUrl;
        $user = Auth::user()->get(); 
        
        $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
            );
        
        
        //Popula a View com os resultados da busca dos registros e o título da página
        return View::make('admin.user.listar', compact('list', 'title', 'class','user', 'breadcrumbs'));
    }
    
    public function getCadastrar()
    {
        
        if(!parent::permissao(self::$classUrl))
                return Redirect::to('admin/sempermissao');
        
        
        //Atribui o título da Página à variável $title
        $title = 'Cadastrar Usu&aacute;rio';
        //Lista todos os ítens de Nav para iterar na listagem
        $showNavs = Nav::getNavs();
        
        $class = self::$classUrl;
        
        $larguraImagemCrop = $this->larguraImagemCrop;
        $alturaImagemCrop = $this->alturaImagemCrop;
        $alturaImagemExibicao = $this->alturaImagemExibicao;
        $larguraImagemExibicao = $this->larguraImagemExibicao;
        
        
        
        $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/cadastrar', 'name' => 'Cadastrar'),
            );
        
         //Popula as informações de $title e $showNavs na View Form   
        return View::make('admin.user.form', compact('title', 'showNavs', 'class', 'breadcrumbs','alturaImagemCrop','larguraImagemCrop','alturaImagemExibicao', 'larguraImagemExibicao'));
    }   
    
    /**
     * @desc Popula as informações para a View Usuario\Form, se o mesmo for uma edição (Edição)
     * @param $id
     * @return View
    **/
    public function getEditar($id = '')
    {   
        if(!parent::permissao(self::$classUrl))
                return Redirect::to('admin/sempermissao');
        
        $class = self::$classUrl;
        
        //Atribui o título da Página à variável $title
        $title = 'Editar '.$this->titulo;    
        //Atribui a $stdClass a classe especificada na variável estática acima
        $stdClass = self::$class;
        //Atribui a variável $data ao registro buscado na tabela, conforme o código
        $data = $stdClass::findOrFail($id);
        //Lista todos os ítens de Nav para iterar na listagem
        $showNavs = Nav::getNavs();
        //Verifica quais itens estão selecionadas ao usuário e atribui à variável $navOptions
        $navOptions = DB::table('nav_item_user')->where('user_id', '=', $id)->lists('nav_item_id');         
        //Popula as informações de $title, $data, $showNavs e $navOptions na View Form    
        
        
        $larguraImagemCrop = $this->larguraImagemCrop;
        $alturaImagemCrop = $this->alturaImagemCrop;
        $alturaImagemExibicao = $this->alturaImagemExibicao;
        $larguraImagemExibicao = $this->larguraImagemExibicao;
         
         
        $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/listar', 'name' => $this->titulo),
                 1 => array( 'url' => self::$classUrl.'/editar/'.$id, 'name' => 'Editar'),
            );
        
        
        return View::make('admin.user.form', compact('title', 'data', 'showNavs', 'class', 'breadcrumbs','alturaImagemCrop','larguraImagemCrop','alturaImagemExibicao', 'larguraImagemExibicao'), array('navOptions' => $navOptions));
    }
        
    /**
     * @desc Efetua a inserção/alteração do registro
     * @return Redirect
    **/
    public function postStore()
    {   
        //Busca todos os inputs que foram postados     
        $input = Input::all();
        //Chama o método de validação que consta em models/Pagina
        $validator = $this->usuario->validate($input);
        //Inicializa a variável $new para posterior verificação se é um registro novo ou de alteração
        $new = true;
        
        //Verifica se existe input hidden de id
        if( Input::has('id') ):
            //Se existe, é uma alteração, então, atribuímos false para a variável
            $new = false;
            //Atribui variável $stdClass a classe descrita na variável estática $class
            $stdClass = self::$class;
            //Faz a busca para retornar os dados já inseridos
            $stdClass = $stdClass::findOrFail( Input::get('id') );
        else:   
            //Se é um registro novo, inicializa a classe especificada 
            $stdClass = new self::$class;
        endif;
        
        //Se houve problemas na validação das informações postadas nos inputs
        if( $validator->fails() ):
            //Redireciona para a View de Cadastro com as mensagens de erros
            return Redirect::back()
                                  ->withInput()
                                  ->withErrors($validator);
        else:
            //Verifica se está editando e se o campo senha está vazio
            if(!$new && $input['password']==''):
                //Se sim, retira do array $input, para não ser alterado no UPDATE
                unset($input['password']);
            endif;
           
            //Itera entre os dados do input enviados
            foreach( $input as $key => $data ):
                /**
                * Verifica se o name é diferente de _token (este input é utilizado como padrão para prevenção de injections), além da confirmação de senha
                * arquivo, listagem de Navs e arquivo, que não são necessários para a primeira iteração
                **/
                if( $key != '_token' && $key !='confirma' && $key != 'navs' && $key != 'image'):
                    //Atribui o valor do campo da base de dados ao valor do input. Caso seja o campo Password, transforma a senha em Hash
                    $stdClass->$key = ($key=='password') ? Hash::make($data) : $data;
                endif; 
            endforeach;
            
           
            //Se o campo arquivo estiver preenchido
            if( Input::hasFile('image') ):
                if(!$new && $stdClass->image <> ''){
                    parent::destroyThumbs($this->thumbs, $this->folder_path.'/'.$stdClass->id, $stdClass->image);
                }
                $stdClass->image;
                $image = Input::file('image');
                $filename = date('YmdHis').'-'.$image->getClientOriginalName();
                $stdClass->image = $filename;
            endif;

            //Salva no banco de dados as informações          
            $stdClass->save();

             parent::prepareDiretory($this->folder_path.'/'.$stdClass->id);

            // Imagem
            if(isset($filename)){
                parent::prepareDiretory($this->folder_path.'/'.$stdClass->id);
                parent::prepareImages($this->thumbs,$image->getRealPath(), $this->folder_path.'/'.$stdClass->id, $filename);
            }
            
            //Verifica se é uma inserção ou alteração e cria um registro na tabela de Logs, informando o título da página, código gerado e input com as informações
            $log = ($new) ? LogAction::createLog($this->titulo, 'inserção', $input['name'].'( '.$stdClass->id.' )') : LogAction::createLog($this->titulo, 'edição', $input['name'].' ( '.$stdClass->id.' )');
            
            //Utiliza variável do Construtor para obter array de Navs checadas
            $this->storeNav = $input['navs'];           
            
            //Se for uma edição, exclui as opções selecionadas anteriormente, conforme código de usuário
            if(!$new):
                DB::table('nav_item_user')->where('user_id', '=', Input::get('id'))->delete();
            endif;
            
            //Faz insert específico para para a tabela de associação nav_item_user
            $stdClass->navitems()->attach($this->storeNav); 
            
            //Redireciona para a listagem com a mensagem de sucesso, conforme o tipo de operação
            return Redirect::to('admin/user/editar/'.$stdClass->id.'/')
                                                   ->with('mensagemSucesso', $new ? 'Cadastro realizado com sucesso!' : 'Altera&ccedil;&atilde;o realizada com sucesso!');                                  
        endif;        
        
    }

    /**
     * @desc Exclui um Usuário, conforme o id repassado
     * @param $id
     * @return Redirect
    **/    
    public function getExcluir($id = '')
    {
         $ids = parent::prepareIds($id);
        
        $countIds = count($ids);
        foreach ($ids as $id){
              //Atribui a $stdClass a classe especificada na variável estática acima
        $stdClass = self::$class;
        //Atribui a variável $getProperties ao registro buscado na tabela, conforme o código
        $getProperties = $stdClass::findOrFail($id); 
        
        parent::destroyDiretory($this->folder_path.'/'.$getProperties->id);
            
        //Exclui o valor da tabela       
        $stdClass::destroy($id);
        //Cria log da ação de exclusão
        LogAction::createLog($this->titulo, 'exclusão', $getProperties->name.'( '.$id.' )');
        //Redireciona para a View de Listagem, com a mensagem de sucesso
        }
        
      
        return Redirect::to('admin/'.self::$classUrl.'/listar')
                                               ->with('mensagemSucesso',($countIds > 1) ? "Itens excluídos com sucesso!": "Item excluido com sucesso!");
        
        
    }
    
    /**
     * @desc Efetua Login no ADMIN
     * @return Redirect
    **/
    public function postLogin()
    {          
        //Se existir o usuário e senha na base de dados
        if(Auth::user()->attempt(array('username' => Input::get('login'), 'password' => Input::get('senha')))): 
            //Altera a data de último acesso na tabela de Usuários
            $this->changeDateAccess();
            //Redireciona para a página principal do ADMIN
            return Redirect::to('/admin/home');
        else:
            //Redireciona para o login, com mensagem de erro
            return Redirect::to('/admin')->with('mensagemErro', 'Usu&aacute;rio e senha incorretos!');
        endif;
    }
    
    /**
     * @desc Efetua Logout no ADMIN
     * @return Redirect
    **/ 
    public function getLogout()
    {
        //Informa data/hora de saída para tabela de Controle de Acessos
        Acesso::logoutEntry();
        //Efetua logout
        Auth::logout();
        //Redireciona para a página de login, com mensagem
        return Redirect::to('admin')->with('mensagemSucesso', 'Voc&ecirc; saiu do sistema com sucesso!');
    }
    
    /**
    *  @desc Altera a data de acesso do usuário ao sistema, na tabela de Usuários
    *  @return void
    */
    public function changeDateAccess()
    {
        //Informa data/hora de entrada para tabela de Controle de Acessos
        Acesso::newEntry(); 
        //Busca os dados do usuário na tabela de Usuários, conforme ID de acesso       
        $user = User::find(Auth::user()->get()->id);
        //Altera data de acesso
        $user->date_access = date('Y-m-d H:i:s'); 
        //Salva no banco de dados
        $user->save();
    }
    
    
    /**
     * Rendezira a pagina de meus dados
     * @return redirect
     */
    public function getMeusdados()
    {   
        $class = self::$classUrl;
        
        //Atribui o título da Página à variável $title
        $title = 'Editar Meus Dados';    
        //Atribui a $stdClass a classe especificada na variável estática acima
        $stdClass = self::$class;
        //Atribui a variável $data ao registro buscado na tabela, conforme o código
        
        $larguraImagemCrop = $this->larguraImagemCrop;
        $alturaImagemCrop = $this->alturaImagemCrop;
        $alturaImagemExibicao = $this->alturaImagemExibicao;
        $larguraImagemExibicao = $this->larguraImagemExibicao;
        
        
        $breadcrumbs = array(
                 0 => array( 'url' => self::$classUrl.'/meusdados', 'name' => 'Meus Dados'),
            );
        
        
        
        $data = $stdClass::findOrFail(Auth::user()->get()->id);
        //Lista todos os ítens de Nav para iterar na listagem 
        //Popula as informações de $title, $data, $showNavs e $navOptions na View Form    
        return View::make('admin.user.meusdados', compact('title', 'data', 'class', 'breadcrumbs','alturaImagemCrop','larguraImagemCrop','alturaImagemExibicao', 'larguraImagemExibicao'));
    }

    
    public function postEditardados()
    {
          //Busca todos os inputs que foram postados     
        $input = Input::all();
        //Chama o método de validação que consta em models/Pagina
        $validator = $this->usuario->validate($input);
        
         $stdClass = self::$class;
        //Faz a busca para retornar os dados já inseridos
        $stdClass = $stdClass::findOrFail( Input::get('id') );
        
          //Se houve problemas na validação das informações postadas nos inputs
        if( $validator->fails() ):
            //Redireciona para a View de Cadastro com as mensagens de erros
            return Redirect::back()
                                  ->withInput()
                                  ->withErrors($validator);
        else:
            //Verifica se está editando e se o campo senha está vazio
            if($input['password']==''):
                //Se sim, retira do array $input, para não ser alterado no UPDATE
                unset($input['password']);
            endif;
           
            //Itera entre os dados do input enviados
            foreach( $input as $key => $data ):
                /**
                * Verifica se o name é diferente de _token (este input é utilizado como padrão para prevenção de injections), além da confirmação de senha
                * arquivo, listagem de Navs e arquivo, que não são necessários para a primeira iteração
                **/
                if( $key != '_token' && $key !='confirma' && $key != 'navs' && $key != 'image'):
                    //Atribui o valor do campo da base de dados ao valor do input. Caso seja o campo Password, transforma a senha em Hash
                    $stdClass->$key = ($key=='password') ? Hash::make($data) : $data;
                endif; 
            endforeach;
            
            //Se o campo arquivo estiver preenchido
            if( Input::hasFile('image') ):
                if($stdClass->image <> ''){
                    parent::destroyThumbs($this->thumbs, $this->folder_path.'/'.$stdClass->id, $stdClass->image);
                }
                $stdClass->image;
                $image = Input::file('image');
                $filename = date('YmdHis').'-'.$image->getClientOriginalName();
                $stdClass->image = $filename;
            endif;

            //Salva no banco de dados as informações          
            $stdClass->save();

             parent::prepareDiretory($this->folder_path.'/'.$stdClass->id);

            // Imagem
            if(isset($filename)){
                parent::prepareDiretory($this->folder_path.'/'.$stdClass->id);
                parent::prepareImages($this->thumbs,$image->getRealPath(), $this->folder_path.'/'.$stdClass->id, $filename);
            }
            
            //Redireciona para a listagem com a mensagem de sucesso, conforme o tipo de operação
            return Redirect::to('admin/user/meusdados/'.$stdClass->id.'/')
                                                   ->with('mensagemSucesso','Altera&ccedil;&atilde;o realizada com sucesso!');      
        
     endif;
    }
    
}

