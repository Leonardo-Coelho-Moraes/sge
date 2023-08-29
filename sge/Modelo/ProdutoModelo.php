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
class ProdutoModelo {
  
 public function armazenar(array $dados): void {
    // Validação dos valores
    $nome = Helpers::textTraco(Helpers::validarString($dados['produto']));  
    $fabricante = Helpers::textTraco(Helpers::validarString($dados['fabricante']));
    $tipo_embalagem = Helpers::validarString($dados['embalagem']);
    $unidades = Helpers::validarNumero($dados['unidade_embalagem']);
    $tipo_produto = Helpers::validarString($dados['tipo']);
    $departamento = Helpers::validarString($dados['departamento']);
    $estoque = Helpers::validarNumero($dados['quantidade']);
    $lote = Helpers::validarString($dados['lote']);
    $fornecedor = Helpers::validarString($dados['fornecedor']);
    $observacao = isset($dados['nota']) ? Helpers::textTraco(Helpers::validarString($dados['nota'])) : null;

    // Verificação de campos inválidos
    if (strlen($nome) < 2 || strlen($fabricante) < 2 || strlen($tipo_embalagem) < 2 || $unidades < 1 || strlen($tipo_produto) < 2 || strlen($departamento) < 2 || $estoque < 1 || strlen($lote) < 1 || strlen($fornecedor) < 2) {
        $mensagem = (new Mensagem)->erro('Preencha todos os campos corretamente, os números precisam ser maiores ou iguais a um e os nomes maiores ou iguais a 2 para as informações serem redundantes!')->flash();
        Helpers::redirecionar('produtos/produto_cadastrar');
        return; // Importante adicionar um "return" aqui para sair da função em caso de erro
    }

    $query = "INSERT INTO produtos (`nome`, `fabricante`, `tipo_embalagem`, `unidades_embalagem`, `tipo_produtos`, `departamento`, `quantidade_estoque`, `lote`, `validade`, `fornecedor`, `observacao`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = Conexao::getInstancia()->prepare($query);

    // Executar a consulta preparada com os valores corretos
    $stmt->execute([$nome, $fabricante, $tipo_embalagem, $unidades, $tipo_produto, $departamento, $estoque, $lote, $dados["validade"], $fornecedor, $observacao]);
}

   public function atualizar(array $dados, int $id): void {
       $nome = Helpers::textTraco(Helpers::validarString($dados['produto']));
    $fabricante = Helpers::textTraco(Helpers::validarString($dados['fabricante']));
    $tipo_embalagem = Helpers::validarString($dados['embalagem']);
    $unidades = Helpers::validarNumero($dados['unidade_embalagem']);
    $tipo_produto = Helpers::validarString($dados['tipo']);
    $departamento = Helpers::validarString($dados['departamento']);
    $estoque = Helpers::validarNumero($dados['quantidade']);
    $lote = Helpers::validarString($dados['lote']);
    $fornecedor = Helpers::validarString($dados['fornecedor']);
    $observacao = isset($dados['nota']) ? Helpers::textTraco(Helpers::validarString($dados['nota'])) : null;
    $editado = 1;
    // Verificação de campos inválidos
    if (strlen($nome) < 2 || strlen($fabricante) < 2 || strlen($tipo_embalagem) < 2 || $unidades < 1 || strlen($tipo_produto) < 2 || strlen($departamento) < 2 || $estoque < 1 || strlen($lote) < 1 || strlen($fornecedor) < 2) {
        $mensagem = (new Mensagem)->erro('Edite todos os campos corretamente, os números precisam ser maiores ou iguais a um e os nomes maiores ou iguais a 2 para as informações serem redundantes!')->flash();
        Helpers::redirecionar('produtos/editar/'.$id);
        return; // Importante adicionar um "return" aqui para sair da função em caso de erro
    }
    
    $query = "UPDATE produtos SET nome = ?, fabricante = ?, tipo_embalagem = ?, unidades_embalagem = ?, tipo_produtos = ?, departamento = ?, quantidade_estoque = ?, lote = ?, validade = ?, fornecedor = ?, observacao = ?, editado = ? WHERE id = ?";
    $stmt = Conexao::getInstancia()->prepare($query);

    $stmt->execute([$nome, $fabricante, $tipo_embalagem, $unidades, $tipo_produto, $departamento, $estoque, $lote, $dados["validade"], $fornecedor, $observacao,$editado, $id]);
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
              OR departamento LIKE :buscar 
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

