<?php

namespace sge\Controlador;

/**
 * Description of SiteControlador
 *
 * @author Leonardo
 */
use sge\Nucleo\Controlador;
use sge\Nucleo\Helpers;
use sge\Modelo\UserModelo;
class AdminLogin extends Controlador {
    

    public function __construct() {
        parent::__construct('templates/site/views');         
    }

   
     public function login(): void {
        
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            if ($this->ChecarDados($dados)){
              $usuario = (new UserModelo())->login($dados);
              if($usuario){
                  Helpers::redirecionar('login');
              }
            }
                
           
        }
        echo $this->template->renderizar('login.html', []);
    }
    private function ChecarDados(array $dados):bool {
        if(empty($dados['usuario'])){
            $this->mensagem->alerta('Campo usuario é obrigatorio!')->flash();
            return false;}
        if(empty($dados['senha'])){
              $this->mensagem->alerta('Campo senha é obrigatorio!')->flash();
            return false;}
           
        return true;
    }
}
