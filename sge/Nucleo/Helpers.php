<?php
namespace sge\Nucleo;
class Helpers{
/**
 * Conta o tempo decorrido de uma data
 * @param string  $data
 * @return string 
 * @copyright (c) 2023, Leonardo Coelho Moraes
 * @author Leonardo
 * 
 */
public static function url(string $url = null): string
{
        $sevidor = filter_input(INPUT_SERVER, 'SERVER_NAME');
        $ambiente = ($sevidor == 'localhost' ? URL_DESENVOLVIMENTO : URL_PRODUCAO);
        if(str_starts_with($url, '/')){
            return $ambiente . $url;
        }
        return $ambiente.'/'.$url;
}
public static function localhost(): bool
{
    $sevidor = filter_input(INPUT_SERVER, 'SERVER_NAME');
    if($sevidor == 'localhost'){
        return true;
    }
    return false;
}
public static function contarTempo(string $data): string {
    $agora = strtotime(date('Y-m-d H:i:s'));
    $tempo = strtotime($data);
    $diferenca = $agora - $tempo;
    $segundos = $diferenca;
    $minutos = round($diferenca / 60);
    $horas = round($diferenca / 3600);
    $dias = round($diferenca / 86400);
    $semanas = round($diferenca / 604800);
    $meses = round($diferenca / 2419200);
    $anos = round($diferenca / 29030400);

    if ($segundos <= 60) {
        return 'agora';
    } elseif ($minutos <= 60) {
        return $minutos == 1 ? 'há 1 minuto' : 'há '.$minutos.' minutos';
    } elseif ($horas <= 24) {
        return $horas == 1 ? 'há 1 hora' : 'há ' . $horas . ' horas';
    } elseif ($dias <= 7) {
        return $dias == 1 ? 'há 1 dia' : 'há ' . $dias . ' dias';
    } elseif ($semanas <= 4) {
        return $semanas == 1 ? 'há 1 semana' : 'há ' . $semanas . ' semanas';
    } elseif ($meses <= 12) {
        return $meses == 1 ? 'há 1 mês' : 'há ' . $meses . ' meses';
    } else {
        return $anos == 1 ? 'há 1 ano' : 'há ' . $anos . ' anos';
    }
}

public static function saudacao(): string {
    $hora = date('H');
   
    $saudacao = match (true){
        $hora >= 0 and $hora <= 5 => 'Boa Madrugada',
        $hora >= 6 and $hora <= 12 => 'Bom dia',
        $hora > 12 and $hora <= 18 => 'Boa Tarde',
        default => 'Boa Noite'
    
    };
    return $saudacao;
}
 
public static function slug(string $string): string {

    $mapa['a'] = 'ÃÁÀÂÄÅÉÈËÊÍÌÎÏÓÒÖÔÕÚÙÜÛÑÇãáàâäåéèëêíìîïóòöôõúùüûñç';
    $mapa['b'] = 'AAAAAAEEEEIIIIOOOOOUUUUNCaaaaaaeeeeiiiiooooouuuunc';
    $slug = strtr(mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8'), mb_convert_encoding($mapa['a'], 'ISO-8859-1', 'UTF-8'), $mapa['b']);
    return strtolower(mb_convert_encoding(trim($slug), 'ISO-8859-1', 'UTF-8'));
}
}
