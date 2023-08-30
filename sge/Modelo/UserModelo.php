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
use PDO;
use sge\Nucleo\Mensagem;
use sge\Nucleo\Conexao;

use sge\Nucleo\Helpers;
use sge\Nucleo\Sessao;
class UserModelo {
     
   public function cadastro(array $dados): void {
         $nivel =  Helpers::validarNumero($dados['nivelacesso']);
       $query = "INSERT INTO usuarios ( `nome`, `senha`, `nivel_acesso` ) VALUES ( ? , ?, ?)";
    $stmt = Conexao::getInstancia()->prepare($query);
    // Verificar e ajustar os valores de marca e tipo

    $stmt->execute([$dados["usuariocad"], $dados["senha"], $nivel]);
}

public function login(array $dados):bool {
   $usuario = $dados['usuario'];
   $senha = $dados['senha'];
    
    $query = "SELECT * FROM `usuarios` WHERE nome = :usuario AND senha = :senha";
    
    $stmt = Conexao::getInstancia()->prepare($query);
    $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
     $stmt->bindValue(':senha', $senha, PDO::PARAM_STR);
    $stmt->execute();
    
    $resultado = $stmt->fetch();
    if(!$resultado ){
    $mensagem = (new Mensagem)->erro('Dados não exitem ou estão incorretos!')->flash();
        return false;
    }
    (new Sessao())->criar('usuarioId', $resultado->id);
   
    
Helpers::redirecionar('');
      return true;
}
}
