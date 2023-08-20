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
class SaidaModelo {
    
     public function busca(int $pagina, int $limite){
        $inicio = ($pagina * $limite) - $limite;
       $query = "SELECT * FROM registros WHERE acao = 'saida' ORDER BY id DESC LIMIT $inicio, $limite";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetchAll();
        
        return $resultado;
    }
   
    public function buscaQuantidade(string $nome): bool|object{
       
        $query= "SELECT * from produtos WHERE nome = {$nome}";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch();
        return $resultado;
    }
    
    public function saida(array $dados): void {
          $produto =  Helpers::validarNumero($dados['produto']);
      $quantidade =  Helpers::validarNumero($dados['quantidade']);
      if ($quantidade <= 0) {
           $mensagem = (new Mensagem)->erro('A quantidade precisa ser maior ou igual 1')->flash();
           Helpers::redirecionar('entrada/adicionar');
        }
       
              $query = "UPDATE produtos SET quantidade_estoque = quantidade_estoque - ? WHERE id = ?";
    $stmt = Conexao::getInstancia()->prepare($query);
    
    // Execute a consulta preparada com os valores apropriados
    $stmt->execute([$quantidade, $produto]);
}

 public function saidaRegisto(array $dados): void {
      $produto =  Helpers::validarNumero($dados['produto']);
      $quantidade =  Helpers::validarNumero($dados['quantidade']);
      $locais =  Helpers::validarNumero($dados['locais']);
      if ($quantidade < 1) {
           $mensagem = (new Mensagem)->erro('A quantidade precisa ser maior ou igual 1')->flash();
           Helpers::redirecionar('entrada/adicionar');
           return;
        }
       $query = "INSERT INTO registros ( `produto_id`, `acao`, `quantidade`, `local_id` ) VALUES ( ? , ?, ?, ?)";
    $stmt = Conexao::getInstancia()->prepare($query);
    // Verificar e ajustar os valores de marca e tipo
   $acao = "saida";
    $stmt->execute([$produto, $acao, $quantidade, $locais]);
}
public function contaRegistros() {
       $query = "SELECT COUNT(*) as total FROM registros WHERE acao = 'saida'";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch(); // Use fetch() em vez de fetchAll()
        return $resultado->total; // Acesse a propriedade diretamente
    }
}
