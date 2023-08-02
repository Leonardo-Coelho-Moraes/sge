<?php
namespace sge\Nucleo;
/**
 * Description of Mensagem
 * Responsavel pela renderização e filtro das mensagens do sistema.
 * @copyright (c) 2023, Leonardo Coelho Moraes
 * @author Leonardo Coelho Moraes
 */

class Mensagem {
   
    private $texto;
    public $css;
    
    public function __toString() {
        return $this->renderizar();
    }


    public function sucesso(string $mensagem):Mensagem {
        $this->css = 'alert alert-success';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }
    public function erro(string $mensagem):Mensagem {
        $this->css = 'alert alert-danger';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }
    public function alerta(string $mensagem):Mensagem {
        $this->css = 'alert alert-warning';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }
    public function informa(string $mensagem):Mensagem {
        $this->css = 'alert alert-info';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }
   

    public function renderizar(): string {
        return "<div class='{$this->css}'>{$this->texto}</div>";
    }

    private function filtrar(string $mensagem): string {
        return filter_var($mensagem, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}
