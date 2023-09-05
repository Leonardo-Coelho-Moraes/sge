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
use sge\Modelo\Atualizar;
use sge\Nucleo\Conexao;
class EntradaModelo {
    public function entrada(array $dados): void {
      $id =  Helpers::validarNumero($dados['produto']);
      $quantidade =  Helpers::validarNumero($dados['quantidade']);
      $dadosArray = array('quantidade' => $quantidade);
      if ($quantidade <= 0) {
        $mensagem = (new Mensagem)->erro('A quantidade precisa ser maior ou igual 1')->flash();
        Helpers::redirecionar('entrada/adicionar');
      }
      (new Atualizar())->atualizar('produtos', "quantidade_estoque = quantidade_estoque + ?", $dadosArray, $id);
}
 public function entradaRegisto(array $dados): void {
       $resultados = Helpers::validadarDados($dados);
               
               $array = array('produto' => $resultados['produto'] , 'acao' => "entrada" , 'quantidade' =>   $resultados['quantidade'], 'local' => 6);
      if ($resultados['quantidade'] < 1) {
           $mensagem = (new Mensagem)->erro('A quantidade precisa ser maior ou igual 1')->flash();
           Helpers::redirecionar('entrada/adicionar');
           return;
        }
       (new Inserir())->inserir('registros', 'produto_id , acao, quantidade, local_id', $array);
      
}
public function contaRegistros() {
       $query = "SELECT COUNT(*) as total FROM registros WHERE acao = 'entrada'";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch(); // Use fetch() em vez de fetchAll()
        return $resultado->total; // Acesse a propriedade diretamente
    }
    public function pesquisa(string $buscar) {
        
       $query = "SELECT * FROM registros WHERE data_hora LIKE '%{$buscar}%' ORDER BY data_hora DESC";
 $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetchAll(); // Use fetch() em vez de fetchAll()
        return $resultado; 

    }


}
