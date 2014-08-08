<?php

class AreaSindicoController extends BaseController {

  protected $parceiro;

  public function __construct(AreaSindico $areaSindico)
  {
    $this->areaSindico = $areaSindico;
  }

  public function sendMail()
  {
             
            $input = Input::all();
            $validator = $this->areaSindico->validate($input);       
            
        if ( $validator->passes() ){

                $infoContato = EmailContato::find(5);

                if(isset( $infoContato->imagem))
                $input['imagem'] = $infoContato->imagem;

                Mail::send('emails.contato.area-sindico', $input, function($m) use ($infoContato,$input)
                {                  
                    $m->from($infoContato->email,'Unifique');         
                    $m->to($infoContato->email,'Unifique');
                    if($infoContato->ccEmail)
                    $m->cc($infoContato->ccEmail);
                   
                    if($infoContato->ccoEmail)
                    $m->bcc($infoContato->ccoEmail);
                    $m->replyTo($input['s_email']);
                    $assunto = 'Contato de interesse - Condomínio';
                     echo 'ok';
                    $m->subject($assunto);
                   
                  });
                       
      }else{
       
        echo 'invalido';
      }

  }
}