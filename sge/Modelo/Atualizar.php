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
class Atualizar {
 public function atualizar(string $tabela,string $atualizar, array $dados, int $id ):  void {
    $query = "UPDATE {$tabela} SET {$atualizar} WHERE id = {$id}";
    
     $stmt = Conexao::getInstancia()->prepare($query);
     $bindParams = [];

foreach ($dados as $valor) {
    $bindParams[] = $valor;
}

$stmt->execute($bindParams);
}


}
