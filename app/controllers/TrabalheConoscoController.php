<?php

class TrabalheConoscoController extends BaseController {

  protected $trabalheConosco;

  public function __construct(TrabalheConosco $trabalheConosco)
  {
    $this->trabalheConosco = $trabalheConosco;
  }

  public function sendMail()
  {
              
            $input = Input::all();
            $validator = $this->trabalheConosco->validate($input);       
            
        if ( $validator->passes() ){

                $vaga = Vaga::getVaga($input['t_vagas']);
                 
                $extensao =  Input::file('t_anexo')->getClientOriginalExtension();
                $arquivoName = Str::slug(date('Yis')).'.'.$extensao;
                Input::file('t_anexo')->move(public_path().'/uploads/trabalhe-conosco/', $arquivoName );
                $input['vaga'] = $vaga->nome;
                $input['arquivo'] = public_path().'/uploads/trabalhe-conosco/'.$arquivoName;

                $infoContato = EmailContato::find(3);

                if(isset( $infoContato->imagem))
                $input['imagem'] = $infoContato->imagem;

                Mail::send('emails.contato.trabalhe-conosco', $input, function($m) use ($infoContato,$input)
                {                  
                    $m->from($infoContato->email,'Unifique');         
                    $m->to($infoContato->email,'Unifique');
                    if($infoContato->ccEmail)
                    $m->cc($infoContato->ccEmail);
                   
                    if($infoContato->ccoEmail)
                    $m->bcc($infoContato->ccoEmail);
                    $m->attach($input['arquivo']);
                    $m->replyTo($input['t_email']);
                    $assunto = 'Contato de interesse para a vaga  "'.$input['vaga'].'"';
                    $m->subject($assunto);
                    echo 'ok';
                  });
                       
      }else{
        echo 'invalido';
      }

  }
}