<?php

class ParceiroController extends BaseController {

  protected $parceiro;

  public function __construct(Parceiro $parceiro)
  {
    $this->parceiro = $parceiro;
  }

  public function sendMail()
  {
             
      $input = Input::all();
      $validator = $this->parceiro->validate($input);       
            
      if ( $validator->passes() ){

          $infoContato = EmailContato::find(4);

          if(isset( $infoContato->imagem))
          $input['imagem'] = $infoContato->imagem;

          Mail::send('emails.contato.seja-parceiro', $input, function($m) use ($infoContato,$input)
          {                  
            $m->from($infoContato->email,'Unifique');         
            $m->to($infoContato->email,'Unifique');
            if($infoContato->ccEmail)
            $m->cc($infoContato->ccEmail);
           
            if($infoContato->ccoEmail)
            $m->bcc($infoContato->ccoEmail);
            $m->replyTo($input['p_email']);
            $assunto = 'Contato de interesse em parceria';
             
            $m->subject($assunto);
            echo 'ok';
          });
                       
      }else{
       
        echo 'invalido';
      }

  }
}