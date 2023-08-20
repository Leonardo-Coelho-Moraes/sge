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
use sge\Nucleo\Conexao;
class UserModelo {
     
   public function cadastro(array $dados): void {
         $nivel =  Helpers::validarNumero($dados['nivelacesso']);
       $query = "INSERT INTO usuarios ( `nome`, `senha`, `nivel_acesso` ) VALUES ( ? , ?, ?)";
    $stmt = Conexao::getInstancia()->prepare($query);
    // Verificar e ajustar os valores de marca e tipo

    $stmt->execute([$dados["usuariocad"], $dados["senha"], $nivel]);
}
}
