<?php

//namespace controllers\Status;

/**
 * Controller responsável por gerenciar a parte de status dos módulos do admin.
 */
class StatusController extends \BaseController
{
    /**
     * Faz a alteração do status de acordo com os parâmetros apresentados.
     */
    public function postStatus($class,$id)
    {
        $class = $class::findOrFail($id);
        
        $idStatus = ($class->status == 1) ? 0 : 1;
        
        $class->status = $idStatus;
        $class->save();
    }
}
