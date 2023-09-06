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
use sge\Nucleo\Helpers;
use sge\Nucleo\Conexao;
use sge\Modelo\Atualizar;
use sge\Modelo\Inserir;
class ProdutoModelo {
  
 public function armazenar(array $dados): void {
    $resultados = Helpers::validadarDados($dados);
       $dadosArray = array(
    'nome' => $resultados['produto'] ,
    'slug' => $resultados['produto'].'-'. uniqid(),
    'fabricante' => $resultados['fabricante'],
    'tipo_embalagem' => $resultados['embalagem'],
    'unidades' => $resultados['unidade_embalagem'],
    'tipo_produto' =>  $resultados['tipo'],
    'modelo' =>  $resultados['modelo'],
    'estoque' => $resultados['quantidade'],
    'lote' => $resultados['lote'],
    'validade' => $resultados['validade'],
    'fornecedor' => $resultados['fornecedor'],
    'observacao' => ($resultados['nota'] ? $resultados['nota'] : '')
);
    // Verificação de campos inválidos
    if (strlen($dadosArray['nome']) < 2 || strlen($dadosArray['fabricante']) < 2 || strlen($dadosArray['tipo_embalagem']) < 2 || $dadosArray['unidades'] < 1 || strlen($dadosArray['tipo_produto']) < 2 || $dadosArray['estoque'] < 1 || strlen($dadosArray['lote']) < 1 || strlen($dadosArray['fornecedor']) < 2) {
        $mensagem = (new Mensagem)->erro('Preencha todos os campos corretamente, os números precisam ser maiores ou iguais a um e os nomes maiores ou iguais a 2 para as informações serem redundantes!')->flash();
        Helpers::redirecionar('produtos/produto_cadastrar');
        return; // Importante adicionar um "return" aqui para sair da função em caso de erro
    }
    (new Inserir())->inserir('produtos', "nome, slug, fabricante, tipo_embalagem, unidades_embalagem, tipo_produtos,modelo, quantidade_estoque, lote, validade, fornecedor, observacao", $dadosArray);
}

   public function atualizar(array $dados, int $id): void {
       $resultados = Helpers::validadarDados($dados);
       $dadosArray = array(
    'nome' => $resultados['produto'] ,
    'slug' => $resultados['produto'].'-'. uniqid(),
    'fabricante' => $resultados['fabricante'],
    'tipo_embalagem' => $resultados['embalagem'],
    'unidades' => $resultados['unidade_embalagem'],
    'tipo_produto' =>  $resultados['tipo'],
    'modelo' =>  $resultados['modelo'],
    'estoque' => $resultados['quantidade'],
    'lote' => $resultados['lote'],
    'validade' => $resultados['validade'],
    'fornecedor' => $resultados['fornecedor'],
    'observacao' => ($resultados['nota']? $resultados['nota'] :null),
    'editado' => 1
);
    // Verificação de campos inválidos
    if (strlen($dadosArray['nome']) < 2 || strlen($dadosArray['fabricante']) < 2 || strlen($dadosArray['tipo_embalagem']) < 2 || $dadosArray['unidades'] < 1 || strlen($dadosArray['tipo_produto']) < 2 || $dadosArray['estoque'] < 1 || strlen($dadosArray['lote']) < 1 || strlen($dadosArray['fornecedor']) < 2) {
        $mensagem = (new Mensagem)->erro('Edite todos os campos corretamente, os números precisam ser maiores ou iguais a um e os nomes maiores ou iguais a 2 para as informações serem redundantes!')->flash();
        Helpers::redirecionar('produtos/editar/'.$id);
        return; // Importante adicionar um "return" aqui para sair da função em caso de erro
    }
    
        (new Atualizar())->atualizar('produtos', "nome = ?, slug = ?, fabricante = ?, tipo_embalagem = ?, unidades_embalagem = ?, tipo_produtos = ?, modelo = ?, quantidade_estoque = ?, lote = ?, validade = ?, fornecedor = ?, observacao = ?, editado = ?", $dadosArray, $id);
    
}
public function deletar(int $id): void {
    $deletado = 1;
    $query = "UPDATE produtos SET deletado = $deletado WHERE `produtos`.`id` = {$id}";
     $stmt = Conexao::getInstancia()->prepare($query);

    $stmt->execute();
    
}
 public function contaRegistros() {
        $query = "SELECT COUNT(*) as total FROM produtos";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch(); // Use fetch() em vez de fetchAll()
        return $resultado->total; // Acesse a propriedade diretamente
    }
   public function pesquisa(string $buscar) {
    $conexao = Conexao::getInstancia();
    
    $query = "SELECT * FROM produtos 
              WHERE nome LIKE :buscar 
              OR fabricante LIKE :buscar 
              OR tipo_embalagem LIKE :buscar 
              OR unidades_embalagem LIKE :buscar 
              OR tipo_produtos LIKE :buscar 
              OR modelo LIKE :buscar 
              OR lote LIKE :buscar 
              OR fornecedor LIKE :buscar 
              ORDER BY nome DESC";
              
    $stmt = $conexao->prepare($query);
    $stmt->bindValue(':buscar', '%' . $buscar . '%', PDO::PARAM_STR);
    $stmt->execute();
    
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
}


   }

