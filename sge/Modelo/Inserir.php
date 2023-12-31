<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace sge\Modelo;

/**
 * Description of Busca
 *
 * @author Leonardo
 */
use sge\Nucleo\Mensagem;
use sge\Nucleo\Helpers;
use sge\Nucleo\Conexao;
class Inserir {
 public function inserir(string $tabela,string $atualizar, array $dados ):  void {
 
 $interrogacoes = implode(', ', array_fill(0, count($dados), '?'));
    // Construa a consulta SQL com as interrogações
    $query = "INSERT INTO {$tabela} ({$atualizar}) VALUES ({$interrogacoes})";

     $stmt = Conexao::getInstancia()->prepare($query);
     $bindParams = [];

foreach ($dados as $valor) {
    $bindParams[] = $valor;
}

$stmt->execute($bindParams);
}


}
