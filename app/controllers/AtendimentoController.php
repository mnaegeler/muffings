<?php

class AtendimentoController extends BaseController {

  protected $atendimento;

  public function __construct(Atendimento $atendimento)
  {
    $this->atendimento = $atendimento;
  }

  public function sendMail()
  {
              
            $input = Input::all();
            $validator = $this->atendimento->validate($input);       
            
        if ( $validator->passes() ){

                $infoContato = EmailContato::find(1);

                if(isset( $infoContato->imagem))
                $input['imagem'] = $infoContato->imagem;

                Mail::send('emails.contato.atendimento', $input, function($m) use ($infoContato,$input)
                {                  
                    $m->from($infoContato->email,'Unifique');         
                    $m->to($infoContato->email,'Unifique');
                    if($infoContato->ccEmail)
                    $m->cc($infoContato->ccEmail);
                   
                    if($infoContato->ccoEmail)
                    $m->bcc($infoContato->ccoEmail);
                      
                    $m->replyTo($input['a_email']);
                    $assunto = 'Atendimento via site';
                    $m->subject($assunto);
                    echo 'ok';
                  });
                       
      }else{
        echo 'invalido';
      }

  }
}