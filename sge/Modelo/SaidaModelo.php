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
use sge\Controlador\UsuarioControlador;
use sge\Nucleo\Conexao;
use sge\Modelo\Inserir;
use sge\Modelo\Atualizar;
class SaidaModelo {
    
    public function buscaQuantidade(string $nome): bool|object{
       
        $query= "SELECT * from produtos WHERE nome = {$nome}";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch();
        return $resultado;
    }
    
    public function saida(array $dados): void {
         $id =  Helpers::validarNumero($dados['produto']);
      $quantidade =  Helpers::validarNumero($dados['quantidade']);
      $dadosArray = array('quantidade' => $quantidade);
      if ($quantidade <= 0) {
           $mensagem = (new Mensagem)->erro('A quantidade precisa ser maior ou igual 1')->flash();
           Helpers::redirecionar('entrada/adicionar');
        }
          
    (new Atualizar())->atualizar('produtos', "quantidade_estoque = quantidade_estoque - ?", $dadosArray, $id);
    
} public function quantidadeSaida(array $dados): void {
      $id =  Helpers::validarNumero($dados['produto']);
      $quantidade =  Helpers::validarNumero($dados['quantidade']);
      $query = "UPDATE produtos SET quantidade_saida = quantidade_saida + ? WHERE id = ?";
    $stmt = Conexao::getInstancia()->prepare($query);
    $stmt->execute([$quantidade, $id]);
       
   
}

 public function saidaRegisto(array $dados): void {
      $resultados = Helpers::validadarDados($dados);
      $user = UsuarioControlador::usuario()->nome;
      $array = array('produto'=>$resultados['produto'],
          'acao'=>"saida",'quantidade'=>$resultados['quantidade'], 'locais'=>$resultados['locais'], 'user'=> $user );

      if ($resultados['quantidade'] < 1) {
           $mensagem = (new Mensagem)->erro('A quantidade precisa ser maior ou igual 1')->flash();
           Helpers::redirecionar('entrada/adicionar');
           return;
        }
       
     (new Inserir())->inserir('registros', 'produto_id, acao, quantidade, local_id, user', $array);
      
}
public function contaRegistros() {
       $query = "SELECT COUNT(*) as total FROM registros WHERE acao = 'saida'";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch(); // Use fetch() em vez de fetchAll()
        return $resultado->total; // Acesse a propriedade diretamente
    }
}
