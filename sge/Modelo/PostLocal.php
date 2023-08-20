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
use sge\Nucleo\Conexao;
class PostLocal {

  
   public function armazenar(array $dados): void {
     
       $local = Helpers::textTraco(Helpers::validarString($dados['local']));
      if (strlen($local) < 2) {
    $mensagem = (new Mensagem)->erro('O nome precisa ter pelo menos dois caracteres.')->flash();
    Helpers::redirecionar('locais');
    return;
}
        
    $query = "INSERT INTO locais (`nome`) VALUES (?)";
    $stmt = Conexao::getInstancia()->prepare($query);
    $stmt->execute([$local]);
}
     
}
