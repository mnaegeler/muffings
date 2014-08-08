<?php

class AssinejaController extends BaseController 
{

  protected $assineja;

  public function __construct(Assineja $assineja)
  {
    $this->assineja = $assineja;
  }

  public function sendMail()
  {
              
            $input = Input::all();
            $validator = $this->assineja->validate($input);       
            
        if ( $validator->passes() ){

                $infoContato = EmailContato::find(7);

                $plano = Plano::find($input['planoId']);
                $input['plano'] = $plano->nome;

                if(isset( $infoContato->imagem))
                $input['imagem'] = $infoContato->imagem;

                Mail::send('emails.contato.assine-ja', $input, function($m) use ($infoContato,$input)
                {                  
                    $m->from($infoContato->email,'Unifique');         
                    $m->to($infoContato->email,'Unifique');
                    if($infoContato->ccEmail)
                    $m->cc($infoContato->ccEmail);
                   
                    if($infoContato->ccoEmail)
                    $m->bcc($infoContato->ccoEmail);
                      
                    $m->replyTo($input['t_email']);
                    $assunto = 'Contato para assinatura';
                    $m->subject($assunto);
                    echo 'ok';
                  });
                       
      }else{
        echo 'invalido';
      }

  }
}