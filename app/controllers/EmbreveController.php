<?php

class EmbreveController extends BaseController {

  protected $emBreve;

  public function __construct(Embreve $emBreve)
  {
    $this->emBreve = $emBreve;
  }

  public function sendMail()
  {
              
            $input = Input::all();
            $validator = $this->emBreve->validate($input);       
            
        if ( $validator->passes() ){

                $infoContato = EmailContato::find(1);

                var_dump($input);exit;

                $servico = Servico::getServico($input['servico']);
                
                $input['servico'] = $servico->nome;

                if(isset( $infoContato->imagem))
                $input['imagem'] = $infoContato->imagem;

                Mail::send('emails.contato.em-breve', $input, function($m) use ($infoContato,$input)
                {                  
                    $m->from($infoContato->email,'Unifique');         
                    $m->to($infoContato->email,'Unifique');
                    if($infoContato->ccEmail)
                    $m->cc($infoContato->ccEmail);
                   
                    if($infoContato->ccoEmail)
                    $m->bcc($infoContato->ccoEmail);
                      
                    $m->replyTo($input['a_email']);
                    $assunto = 'Contato de interesse no serviço "('.$input['servico'].')"';
                    $m->subject($assunto);
                    echo 'ok';
                  });
                       
      }else{
        echo 'invalido';
      }

  }
}