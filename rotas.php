<?php
 use Pecee\SimpleRouter\SimpleRouter;
use sge\Nucleo\Helpers;
try {
    SimpleRouter::setDefaultNamespace('sge\Controlador');
SimpleRouter::get(URL_SITE,'SiteControlador@index');
SimpleRouter::get(URL_SITE . 'index.php', function() {
        Helpers::redirecionar();
    });
SimpleRouter::get(URL_SITE.'entrada','SiteControlador@entrada');
SimpleRouter::match(['get','post'],URL_SITE.'entrada/adicionar','SiteControlador@entrada_adicionar');
SimpleRouter::match(['get','post'],URL_SITE.'entrada/editar/{id}','SiteControlador@editar_entrada');
SimpleRouter::get(URL_SITE.'saida','SiteControlador@saida');
SimpleRouter::match(['get','post'],URL_SITE.'saida/adicionar','SiteControlador@saida_adicionar');
SimpleRouter::match(['get','post'],URL_SITE.'saida/editar/{id}','SiteControlador@editar_saida');
SimpleRouter::get(URL_SITE.'produtos','SiteControlador@produtos');

SimpleRouter::match(['get','post'],URL_SITE.'produtos/produto_cadastrar','SiteControlador@produto_cadastrar');
SimpleRouter::match(['get','post'],URL_SITE.'produtos/editar/{id}','SiteControlador@editar_produto');
SimpleRouter::match(['get','post'],URL_SITE.'produtos/deletar/{id}','SiteControlador@deletar_produto');
SimpleRouter::match(['get', 'post'], URL_SITE . 'produtos/{id}', 'SiteControlador@produto');

SimpleRouter::get(URL_SITE.'registros','SiteControlador@registros');
SimpleRouter::match(['get','post'],URL_SITE.'usuarios','SiteControlador@usuarios');
SimpleRouter::match(['get','post'],URL_SITE.'locais','SiteControlador@locais');
SimpleRouter::match(['get','post'],URL_SITE.'local/'.'{id}','SiteControlador@local');
SimpleRouter::match(['get','post'],URL_SITE.'relatorio','SiteControlador@relatorio');
SimpleRouter::get(URL_SITE.'relatorio/download','SiteControlador@download');
SimpleRouter::get(URL_SITE.'erro404','SiteControlador@erro404');
SimpleRouter::post(URL_SITE.'buscar','SiteControlador@buscarRegistros');
SimpleRouter::post(URL_SITE.'buscarProdutos','SiteControlador@buscarProdutos');
SimpleRouter::match(['get','post'],URL_SITE.'login','AdminLogin@login');

SimpleRouter::start();
} catch (Pecee\SimpleRouter\Exceptions\NotFoundHttpException $ex) {
    if(Helpers::localhost()){
        echo $ex;
    }
  
    else{
     Helpers::redirecionar('erro404');}
     
}
