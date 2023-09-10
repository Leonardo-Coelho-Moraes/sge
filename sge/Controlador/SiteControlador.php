<?php

namespace sge\Controlador;

/**
 * Description of SiteControlador
 *
 * @author Leonardo
 */

use sge\Controlador\UsuarioControlador;
use sge\Modelo\Busca;
use sge\Modelo\Contar;
use sge\Modelo\EntradaModelo;
use sge\Modelo\PostLocal;
use sge\Modelo\ProdutoModelo;
use sge\Modelo\RegistrosModelo;
use sge\Modelo\SaidaModelo;
use sge\Modelo\UserModelo;
use sge\Nucleo\Controlador;
use sge\Nucleo\Helpers;
use sge\Nucleo\Sessao;
class SiteControlador extends Controlador {
     private $sessao;
     protected $usuario;
     protected $user;
     protected $nivel_user;


     public function __construct() {
        parent::__construct('templates/site/views');
        $this->usuario = UsuarioControlador::usuario();
        if(!$this->usuario)
        {
            $this->mensagem->erro('Faça o login para acessar o sistema!')->flash();
             Helpers::redirecionar('login');
             $limpar = (new Sessao())->limpar('usuarioId');
        }
          
        $this->nivel_user = UsuarioControlador::usuario()->nivel_acesso;
           $this->sessao = new Sessao();
           (new UsuarioControlador())->limpar_usuario();
           
           
           $this->user = UsuarioControlador::usuario()->nome;   
           
    }

    public function index(): void {
        
        if($this->usuario->nivel_acesso >3){
        echo $this->template->renderizar('index.html', [ 'titulo' => 'SGE-SEMSA Dashboard']);}
        else{
        Helpers::redirecionar('entrada');}
    }

    public function entrada(): void {
      
        
        $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $limite = 30;
         $produtos = (new Busca())->busca(null,null,'produtos',null,'nome ASC',null);
          $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
          $quantidade = (new Contar())->contar('registros',"acao = 'entrada'");
          $soma =  (new RegistrosModelo())->somarQuantidades('registros', "acao = 'entrada'");
          $edicao = (new Contar())->contar('registros',"editado = 1 AND acao = 'entrada'");
          
          
        $registros = (new Busca())->busca($pagina, $limite,'registros', "acao = 'entrada'", 'data_hora DESC');
        $totalRegistros = (new EntradaModelo())->contaRegistros();
        $totalPaginas = ceil($totalRegistros / $limite);
     
        

        echo $this->template->renderizar('entrada.html', [ 'titulo' => 'SGE-SEMSA Entrada', 'registros' => $registros,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas, 'produtos'=> $produtos, 'locais'=>$locais, 'quantidade' =>$quantidade,'edicao' =>$edicao, 'soma' => $soma]);
    }

    public function entrada_adicionar(): void {
        $produtos = (new Busca())->busca(null,null,'produtos',"deletado != 1 OR deletado IS NULL ",'nome ASC',null);
        $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            (new EntradaModelo())->entrada($dados);
            (new EntradaModelo())->entradaRegisto($dados);
            $this->mensagem->sucesso('Entrada Adicionada com Sucesso!')->flash();
            Helpers::redirecionar('entrada/adicionar');
        }
        echo $this->template->renderizar('formularios/adicionarentrada.html', [ 'titulo' => 'SGE-SEMSA Produtos', 'produtos' => $produtos, 'locais' => $locais]);
    }

    public function editar_entrada(int $id): void {
         if($this->nivel_user > 2){
       $produtos = (new Busca())->busca(null,null,'produtos',"deletado != 1 OR deletado IS NULL ",'nome ASC',null);
        $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
        $registros = (new Busca())->buscaId('registros',$id);
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
$this->mensagem->sucesso('Registro Editado com Sucesso. Lembre de atualizar a quantidade do estoque em produtos!')->flash();
            (new RegistrosModelo())->atualizar($dados, $id);
            Helpers::redirecionar('entrada');
        }
         echo $this->template->renderizar('formularios/editarentrada.html', [ 'titulo' => 'SGE-SEMSA Produtos', 'registros' => $registros, 'produtos' => $produtos, 'locais' => $locais]);}
         else{
    
            Helpers::redirecionar('entrada');
}
    }

    public function saida(): void {
        $produtos = (new Busca())->busca(null,null,'produtos',null,'nome ASC',null);
        $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
          $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $limite = 30;
         $quantidade = (new Contar())->contar('registros',"acao = 'saida'");
         $edicao = (new Contar())->contar('registros',"editado = 1 AND acao = 'saida'");
         $soma =  (new RegistrosModelo())->somarQuantidades('registros', "acao = 'saida'");
        $registros = (new Busca())->busca($pagina, $limite,'registros', "acao = 'saida'", 'data_hora DESC');
        $totalRegistros = (new SaidaModelo())->contaRegistros();

        $totalPaginas = ceil($totalRegistros / $limite);

        echo $this->template->renderizar('saida.html', [ 'titulo' => 'SGE-SEMSA Saída', 'registros' => $registros,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas, 'produtos'=> $produtos, 'locais'=>$locais, 'quantidade' =>$quantidade, 'edicao' => $edicao,'soma' => $soma]);
    }

    public function saida_adicionar(): void {
        $produtos = (new Busca())->busca(null,null,'produtos',"deletado != 1 OR deletado IS NULL ",'nome ASC',null);
        $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados) ) {
            (new SaidaModelo())->saida($dados);
            (new SaidaModelo())->quantidadeSaida($dados);
            (new SaidaModelo())->saidaRegisto($dados);
            $this->mensagem->sucesso('Saída Adicionada com Sucesso!')->flash();
            Helpers::redirecionar('saida/adicionar');
        }
          
        
        echo $this->template->renderizar('formularios/adicionarsaida.html', [ 'titulo' => 'SGE-SEMSA Produtos', 'produtos' => $produtos, 'locais' => $locais]);
    }

    public function editar_saida(int $id): void {
          if($this->nivel_user > 2){
      $produtos = (new Busca())->busca(null,null,'produtos',"deletado != 1 OR deletado IS NULL ",'nome ASC',null);
        $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
        $registros = (new Busca())->buscaId('registros',$id);
        
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {

            (new RegistrosModelo())->atualizar($dados, $id);
            $this->mensagem->sucesso('Registro Editado com Sucesso. Lembre de atualizar a quantidade do estoque em produtos e quantidade de saídas!')->flash();
         
            Helpers::redirecionar('saida');
        }
          echo $this->template->renderizar('formularios/editarsaida.html', [ 'titulo' => 'SGE-SEMSA Produtos', 'registros' => $registros, 'produtos' => $produtos, 'locais' => $locais]);}
          else{
  
            Helpers::redirecionar('saida');
}
    }

    public function produtos(): void {
        $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $limite = 30;
         $produtos = (new Busca())->busca($pagina, $limite,'produtos', "deletado != 1 OR deletado IS NULL ",'criado_em DESC');
           $quantidade = (new Contar())->contar('produtos',"deletado = 0 OR deletado IS NULL");
          $edicao = (new Contar())->contar('produtos',"editado = 1");
          
          $deletado = (new Contar())->contar('produtos',"deletado = 1");
        $totalRegistros = (new ProdutoModelo())->contaRegistros();
        $totalPaginas = ceil($totalRegistros / $limite);
       

        echo $this->template->renderizar('produtos.html', [ 'titulo' => 'SGE-SEMSA Produtos', 'produtos' => $produtos,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas,'quantidade' =>$quantidade,'edicao' =>$edicao, 'deletado' => $deletado]);
    }

    public function produto_cadastrar(): void {

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            (new ProdutoModelo())->armazenar($dados);
             $this->mensagem->sucesso('Registro Editado com Sucesso. Lembre de atualizar a quantidade do estoque em produtos!')->flash();
            Helpers::redirecionar('produtos');
        }

        echo $this->template->renderizar('formularios/cadastrarproduto.html', [ 'titulo' => 'SGE-SEMSA Produtos']);
    }

    public function editar_produto(string $slug, int $id): void {
         if($this->nivel_user > 2){
        $produtos = (new Busca())->buscaSlug('produtos',$slug);
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            (new ProdutoModelo())->atualizar($dados, $id);
            $this->mensagem->sucesso('Registro Editado com Sucesso. Lembre de atualizar a quantidade do estoque em produtos!')->flash();
            Helpers::redirecionar('produtos');
        }


        echo $this->template->renderizar('formularios/editarproduto.html', [ 'titulo' => 'SGE-SEMSA Produtos', 'produto' => $produtos]);
    }
     else{
   
            Helpers::redirecionar('produtos');
}
        }

    public function deletar_produto(string $slug, int $id): void {
        
         if($this->nivel_user > 2){
        (new ProdutoModelo())->deletar($id);
        $this->mensagem->sucesso('Produto inserido na lista de deletados com sucesso!')->flash();
         Helpers::redirecionar('produtos');}
    }
    
    public function produto(string $slug, int $id): void {
       
        $produto =   (new Busca())->buscaSlug('produtos',$slug);
        $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $limite = 30;
       $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
          $registros = (new Busca())->busca($pagina, $limite,'registros',"produto_id = $id",null);
        $totalRegistros = (new RegistrosModelo())->contaRegistrosIdProduto($id);
        $nome = Helpers::slug($produto->nome);
       
        $totalPaginas = ceil($totalRegistros / $limite);
        if (!$produto) {
            Helpers::redirecionar("erro404");
        }
        echo $this->template->renderizar('produto.html', [ 'titulo' => 'SGE-SEMSA ' . $nome, 'registros' => $registros, 'produto' => $produto,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas, 'total' => $totalRegistros, 'locais'=> $locais]);
      
    }


    public function registros(): void {
       $produtos = (new Busca())->busca(null,null,'produtos',null,'nome ASC',null);
        $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
        $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $limite = 30;
        $registros = (new Busca())->busca($pagina, $limite,'registros','','id DESC');
        
        $totalRegistros = (new RegistrosModelo())->contaRegistros();

        $totalPaginas = ceil($totalRegistros / $limite);
        $quantidadeEntradas = (new Contar())->contar('registros',"acao = 'entrada'");
         $quantidadeSaidas = (new Contar())->contar('registros',"acao = 'saida'");
         $edicao = (new Contar())->contar('registros',"editado = 1");
         $soma =  (new RegistrosModelo())->somarQuantidades('registros', null);
        echo $this->template->renderizar('registros.html', [ 'titulo' => 'SGE-SEMSA Registros', 'registros' => $registros, 'produtos' => $produtos, 'locais' => $locais,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas, 'quantidadeEntradas' => $quantidadeEntradas, 'quantidadeSaidas' => $quantidadeSaidas, 'edicao' => $edicao, 'soma'=>$soma]);
    }

    public function usuarios(): void {
        if($this->usuario->nivel_acesso >1){
     $usuarios = (new Busca())->busca(null,null,'usuarios','deletado = 0','criado_em DESC',null);
         $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            (new UserModelo())->cadastro($dados);
            Helpers::redirecionar('usuarios');
        }

        echo $this->template->renderizar('usuarios.html', [ 'titulo' => 'SGE-SEMSA Usuários', 'usuarios' => $usuarios]);}
        else{ 
             
            Helpers::redirecionar('entrada');}
   
    }

    public function locais(): void {
    
        $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            (new PostLocal())->armazenar($dados);

            Helpers::redirecionar('locais');
        }
        echo $this->template->renderizar('locais.html', [ 'titulo' => 'SGE-SEMSA Locais', 'locais' => $locais]);
    }

    public function local(string $nome, int $id): void {
        $locais = (new Busca())->buscaId('locais',$id);
      $produtos = (new Busca())->busca(null,null,'produtos',null,'nome ASC',null);
       
         $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $limite = 30;
       $registros = (new Busca())->busca($pagina, $limite,'registros',"local_id = $id",'id DESC');
        $totalRegistros = (new RegistrosModelo())->contaRegistrosIdLocal($id);
        $nome = Helpers::slug($locais->nome) ;
       
        $totalPaginas = ceil($totalRegistros / $limite);
        if (!$locais) {
            Helpers::redirecionar("erro404");
        }
        echo $this->template->renderizar('local.html', [ 'titulo' => 'SGE-SEMSA ' . $nome, 'registros' => $registros, 'produtos' => $produtos, 'locais' => $locais,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas, 'total' => $totalRegistros]);
    }
    
    public function erro404(): void {
        echo $this->template->renderizar('error404.html', [ 'titulo' => 'Página não Encontrada']);
    }

    public function buscarRegistros(): void {
      $produtos = (new Busca())->busca(null,null,'produtos',null,'nome ASC',null);
        $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
        $buscar = filter_input(INPUT_POST, 'pesquisa', FILTER_DEFAULT);
        if (isset($buscar)) {
            $pesquisas = (new EntradaModelo())->pesquisa($buscar);
           
        }
        echo $this->template->renderizar('buscar.html', [ 'titulo' => 'Página não Encontrada', 'pesquisas'=>$pesquisas,'produtos'=> $produtos, 'locais'=>$locais]);
    }
    
     public function buscarProdutos(): void {
        
        $buscar = filter_input(INPUT_POST, 'pesquisa', FILTER_DEFAULT);
        if (isset($buscar)) {
            $pesquisas = (new ProdutoModelo())->pesquisa($buscar);
           
        }
        echo $this->template->renderizar('buscarProdutos.html', [ 'titulo' => 'Página não Encontrada', 'produtos'=>$pesquisas]);
    }
    public function sair(): void {
        $this->sessao->limpar('usuarioId');
         $this->mensagem->sucesso('Você deslogou do sistema!')->flash();
        Helpers::redirecionar('login');
       
    }
}
