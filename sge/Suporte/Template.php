<?php

namespace sge\Suporte;
use sge\Nucleo\Helpers;
use sge\Controlador\UsuarioControlador;
use Twig\Lexer;
class Template
{
    private \Twig\Environment $twig;
    public function __construct(string $diretorio)
    {
        $this->twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader($diretorio));
        $this->twig->setLexer(new Lexer($this->twig, array($this->helpers())));

    }
    public function renderizar(string $view, array $dados){
        return $this->twig->render($view, $dados);
    }
    private function helpers():void{
        array(
            $this->twig->addFunction(new \Twig\TwigFunction('saudacao', function(){
                return Helpers::saudacao();
            })), $this->twig->addFunction(new \Twig\TwigFunction('url', function (string $url = null) {
                return Helpers::url($url);
            })),
                $this->twig->addFunction(new \Twig\TwigFunction('juntarLink', function (string $string = null) {
                return Helpers::juntarlink($string);
            })),
                    $this->twig->addFunction(new \Twig\TwigFunction('slug', function (string $string = null) {
                return Helpers::slug($string);
            })),
                     $this->twig->addFunction(new \Twig\TwigFunction('maiuscula', function (string $string = null) {
                return ucwords($string);
            })),
                         $this->twig->addFunction(new \Twig\TwigFunction('flash', function () {
                return Helpers::flash();
            })),
                             $this->twig->addFunction(new \Twig\TwigFunction('usuario', function () {
                return UsuarioControlador::usuario();
            })),
                        $this->twig->addFunction(new \Twig\TwigFunction('usuarioReduzido', function (string $string = null, int $max = null) {
                return Helpers::userLogo($string, $max);
            })),
                  
        );
    }
}
