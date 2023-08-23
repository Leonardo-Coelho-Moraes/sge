<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace sge\Modelo;

/**
 * Description of Contar
 *
 * @author Leonardo
 */
use sge\Nucleo\Mensagem;
use sge\Nucleo\Helpers;
use sge\Nucleo\Conexao;
class Contar {
 public function contar( string $tabela, ?string $termo = null): int {
   

    $termoClause = ($termo ? "WHERE $termo" : '');
   

    $query = "SELECT COUNT(*) as total FROM {$tabela} {$termoClause}";
    $stmt = Conexao::getInstancia()->query($query);
     $resultado = $stmt->fetch(); // Use fetch() em vez de fetchAll()
        return $resultado->total;
}

}
