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
class Busca {
 public function busca(?int $pagina = null,?int $limite = null, string $tabela, ?string $termo = null, ?string $ordem = null, ?string $limit = null): array {
    $inicio = ($pagina !== null && $limite !== null) ? (($pagina - 1) * $limite) : 0;

    $termoClause = ($termo ? "WHERE $termo" : '');
    $ordemClause = ($ordem ? "ORDER BY $ordem" : '');
    $limitClause = ($limit !== null) ? "LIMIT $limit" : (($pagina !== null && $limite !== null) ? "LIMIT $inicio, $limite" : '');

    $query = "SELECT * FROM {$tabela} {$termoClause} {$ordemClause} {$limitClause}";
    $stmt = Conexao::getInstancia()->query($query);
    $resultado = $stmt->fetchAll();

    return $resultado;
}
public function buscaId( string $tabela, int $id): bool|object{

        $query= "SELECT * from {$tabela} WHERE id = {$id}";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch();
        
        return $resultado;
    }

}
