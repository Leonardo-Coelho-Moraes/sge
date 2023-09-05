<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace sge\Modelo;
/**
 * Description of PostLocal
 *
 * @author Leonardo
 */
use sge\Nucleo\Mensagem;
use sge\Nucleo\Helpers;
use sge\Modelo\Inserir;
use sge\Nucleo\Conexao;
class PostLocal {

  
   public function armazenar(array $dados): void {
     $resultados = Helpers::validadarDados($dados);
     
     
       $array =array('local'=>$resultados['local']);
      if (strlen($resultados['local']) < 2) {
    $mensagem = (new Mensagem)->erro('O nome precisa ter pelo menos dois caracteres.')->flash();
    Helpers::redirecionar('locais');
    return;
   
}
 (new Inserir())->inserir('locais', 'nome', $array);
   }
    

     
}
