<?php

class LigamosPraVoceController extends BaseController {

  protected $ligamosPraVoce;

  public function __construct(LigamosPraVoce $ligamosPraVoce)
  {
    $this->ligamosPraVoce = $ligamosPraVoce;
  }

  public function sendMail()
  {
              
            $input = Input::all();
            $validator = $this->ligamosPraVoce->validate($input);       
            
        if ( $validator->passes() ){

              $agenda = new AgendaLigacoes();
              $agenda->nome = $input['l_nome'];
              $agenda->telefone = $input['l_telefone'];
              $agenda->horario = $input['l_horario'];
              $agenda->save();

                $infoContato = EmailContato::find(2);

                if(isset( $infoContato->imagem))
                $input['imagem'] = $infoContato->imagem;

                Mail::send('emails.contato.ligamos-para-voce', $input, function($m) use ($infoContato,$input)
                {                  
                    $m->from($infoContato->email,'Unifique');         
                    $m->to($infoContato->email,'Unifique');
                    if($infoContato->ccEmail)
                    $m->cc($infoContato->ccEmail);
                   
                    if($infoContato->ccoEmail)
                    $m->bcc($infoContato->ccoEmail);
                      
                    $assunto = 'Solicitação de contato via telefone';
                    $m->subject($assunto);
                    echo 'ok';
                  });
                       
      }else{
        echo 'invalido';
      }

  }
}