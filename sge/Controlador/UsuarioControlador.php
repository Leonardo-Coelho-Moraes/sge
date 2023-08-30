<?php

namespace sge\Controlador;

/**
 * Description of SiteControlador
 *
 * @author Leonardo
 */
use sge\Nucleo\Controlador;

use sge\Nucleo\Sessao;
use sge\Modelo\Busca;

class UsuarioControlador extends Controlador {
    

    public function __construct() {
        parent::__construct('templates/site/views');         
    }

   
     public static function usuario() {
      $sessao=  new Sessao();
      if(!$sessao->checar('usuarioId')){
          return null;
          
      }
      return (new Busca())->buscaId('usuarios', $sessao->usuarioId) ;
    }
   
}
