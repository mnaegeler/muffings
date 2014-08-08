<?php

class OrcamentoController extends BaseController {

	protected $nav;

  public function __construct(Orcamento $orcamento)
  {
    $this->orcamento = $orcamento;
  }

	public function sendMail()
	{
		          //Busco os dadoa do formulário e jogo num array para uso em template de envio de e-mail
               $data = array();
               $data['nome']          = Input::get("nome");
               $data['email']         = Input::get("email");
               $data['telefone']      = Input::get("telefone");
               $data['empresa']       = Input::get("empresa");
               $data['cidade']        = Input::get("cidade");
               $data['estado']        = Input::get("estado");
               $data['msg']           = Input::get("msg");
                
               $validator = $this->orcamento->validate($data);               
                
               $email = EmailContato::find(1);
               $data['emailDestinatario']   = $email->email;
               $data['ccEmailDestinatario']  = $email->ccEmail;
               $data['ccoEmailDestinatario'] = $email->ccoEmail;


               if ( $validator->passes() ):
                       /*Envio e-mail
                        * @param1: View que conterá o template de e-mail que será enviado ao destinatário 
                        * @param2: Dados advindos do array acima, que contém os dados do input
                        * @param3: Função inicializadora do callback de envio
                        * 
                        **/
                       Mail::send('emails.contato.orcamento', $data, function($m) use ($data)
                       {		                           
                           $m->from($data['emailDestinatario'], $data['nome']);			   
                           $m->to($data['emailDestinatario']);
                              if($data['ccEmailDestinatario']):
                                $m->cc($data['ccEmailDestinatario']);
                              endif; 
                              if($data['ccoEmailDestinatario']):
                                $m->bcc($data['ccoEmailDestinatario']);
                              endif;    
                             $m->replyTo($data['email'] );
                             $m->subject('Email Orçamento : '.  $data['nome'] );
                       });
                       return Redirect::to('/orcamento')->with('message','Este orçamento foi enviado com sucesso!'); 
                else:
                       return Redirect::to('/orcamento')->withErrors($validator);
                endif;
                
                
	}

}