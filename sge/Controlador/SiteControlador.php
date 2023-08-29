<?php

namespace sge\Controlador;

/**
 * Description of SiteControlador
 *
 * @author Leonardo
 */
use FPDF;

use sge\Modelo\PostLocal;
use sge\Modelo\Busca;
use sge\Nucleo\Controlador;
use sge\Nucleo\Helpers;
use sge\Modelo\EntradaModelo;
use sge\Modelo\SaidaModelo;
use sge\Modelo\ProdutoModelo;
use sge\Modelo\RegistrosModelo;
use sge\Modelo\UserModelo;
use sge\Modelo\Contar;
use sge\Modelo\RelatorioModelo;
use sge\Nucleo\Sessao;
class SiteControlador extends Controlador {
     private $sessao;

    public function __construct() {
        parent::__construct('templates/site/views');
           $this->sessao = new Sessao();
    }

    public function index(): void {
      
        echo $this->template->renderizar('index.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA Dashboard']);
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
     
        

        echo $this->template->renderizar('entrada.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA Entrada', 'registros' => $registros,
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
        echo $this->template->renderizar('formularios/adicionarentrada.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA Produtos', 'produtos' => $produtos, 'locais' => $locais]);
    }

    public function editar_entrada(int $id): void {
       $produtos = (new Busca())->busca(null,null,'produtos',"deletado != 1 OR deletado IS NULL ",'nome ASC',null);
        $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
        $registros = (new Busca())->buscaId('registros',$id);
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
$this->mensagem->sucesso('Registro Editado com Sucesso. Lembre de atualizar a quantidade do estoque em produtos!')->flash();
            (new RegistrosModelo())->atualizar($dados, $id);
            Helpers::redirecionar('entrada');
        }
        echo $this->template->renderizar('formularios/editarentrada.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA Produtos', 'registros' => $registros, 'produtos' => $produtos, 'locais' => $locais]);
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

        echo $this->template->renderizar('saida.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA Saída', 'registros' => $registros,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas, 'produtos'=> $produtos, 'locais'=>$locais, 'quantidade' =>$quantidade, 'edicao' => $edicao,'soma' => $soma]);
    }

    public function saida_adicionar(): void {
        $produtos = (new Busca())->busca(null,null,'produtos',"deletado != 1 OR deletado IS NULL ",'nome ASC',null);
        $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados) ) {
            (new SaidaModelo())->saida($dados);
            (new SaidaModelo())->saidaRegisto($dados);
            $this->mensagem->sucesso('Saída Adicionada com Sucesso!')->flash();
            Helpers::redirecionar('saida/adicionar');
        }
          
        
        echo $this->template->renderizar('formularios/adicionarsaida.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA Produtos', 'produtos' => $produtos, 'locais' => $locais]);
    }

    public function editar_saida(int $id): void {
      $produtos = (new Busca())->busca(null,null,'produtos',"deletado != 1 OR deletado IS NULL ",'nome ASC',null);
        $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
        $registros = (new Busca())->buscaId('registros',$id);
        
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {

            (new RegistrosModelo())->atualizar($dados, $id);
            $this->mensagem->sucesso('Registro Editado com Sucesso. Lembre de atualizar a quantidade do estoque em produtos!')->flash();
         
            Helpers::redirecionar('saida');
        }
        echo $this->template->renderizar('formularios/editarsaida.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA Produtos', 'registros' => $registros, 'produtos' => $produtos, 'locais' => $locais]);
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
       

        echo $this->template->renderizar('produtos.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA Produtos', 'produtos' => $produtos,
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

        echo $this->template->renderizar('formularios/cadastrarproduto.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA Produtos']);
    }

    public function editar_produto(int $id): void {

        $produtos = (new Busca())->buscaId('produtos',$id);
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            (new ProdutoModelo())->atualizar($dados, $id);
            $this->mensagem->sucesso('Registro Editado com Sucesso. Lembre de atualizar a quantidade do estoque em produtos!')->flash();
            Helpers::redirecionar('produtos');
        }


        echo $this->template->renderizar('formularios/editarproduto.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA Produtos', 'produto' => $produtos]);
    }

    public function deletar_produto(int $id): void {
        
        
        (new ProdutoModelo())->deletar($id);
        $this->mensagem->sucesso('Produto inserido na lista de deletados com sucesso!')->flash();
        Helpers::redirecionar('produtos');
    }
    
    public function produto(int $id): void {
       
        $produto =   (new Busca())->buscaId('produtos',$id);
        $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $limite = 30;
       
          $registros = (new Busca())->busca($pagina, $limite,'registros',"produto_id = $id",'id DESC');
        $totalRegistros = (new RegistrosModelo())->contaRegistrosId($id);
        $nome = Helpers::slug($produto->nome);
       
        $totalPaginas = ceil($totalRegistros / $limite);
        if (!$produto) {
            Helpers::redirecionar("erro404");
        }
        echo $this->template->renderizar('produto.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA ' . $nome, 'registros' => $registros, 'produto' => $produto,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas, 'total' => $totalRegistros]);
      
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
        echo $this->template->renderizar('registros.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA Registros', 'registros' => $registros, 'produtos' => $produtos, 'locais' => $locais,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas, 'quantidadeEntradas' => $quantidadeEntradas, 'quantidadeSaidas' => $quantidadeSaidas, 'edicao' => $edicao, 'soma'=>$soma]);
    }

    public function usuarios(): void {
        $usuarios = (new Busca())->busca(null,null,'usuarios',null,'criado_em DESC',null);
         $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            (new UserModelo())->cadastro($dados);
            Helpers::redirecionar('usuarios');
        }

        echo $this->template->renderizar('usuarios.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA Usuários', 'usuarios' => $usuarios]);
    }

    public function locais(): void {
    
        $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            (new PostLocal())->armazenar($dados);

            Helpers::redirecionar('locais');
        }
        echo $this->template->renderizar('locais.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA Locais', 'locais' => $locais]);
    }

    public function local(int $id): void {
        $locais = (new Busca())->buscaId('locais',$id);
      $produtos = (new Busca())->busca(null,null,'produtos',null,'nome ASC',null);
       
         $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $limite = 30;
       $registros = (new Busca())->busca($pagina, $limite,'registros',"local_id = $id",'id DESC');
        $totalRegistros = (new RegistrosModelo())->contaRegistrosId($id);
        $nome = Helpers::slug($locais->nome) ;
       
        $totalPaginas = ceil($totalRegistros / $limite);
        if (!$locais) {
            Helpers::redirecionar("erro404");
        }
        echo $this->template->renderizar('local.html', ['usuario' => 'Leonardo', 'titulo' => 'SGE-SEMSA ' . $nome, 'registros' => $registros, 'produtos' => $produtos, 'locais' => $locais,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas, 'total' => $totalRegistros]);
    }

 public function relatorio(): void {
    $locais = (new Busca())->busca(null, null, 'locais', null, 'nome ASC', null);
    $relatorio = [];
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
    if (isset($dados)) {
        $relatorio = (new RelatorioModelo())->buscaRegistros($dados);
        if(empty($relatorio)){
              $this->mensagem->erro('Registros não exitem, consulte o banco de dados!')->flash();
        }
      
    }

  $this->sessao->criar('relatorio', $relatorio);
    echo $this->template->renderizar('relatorio.html', [
        'usuario' => 'Leonardo',
        'titulo' => 'SGE-SEMSA Relatório',
        'relatorio' => $relatorio,
        'locais' => $locais
    ]);
}

public function download(): void {
    $sessao = (new Sessao())->carregar();
    $relatorio = $sessao->relatorio;
    
    if (!empty($relatorio)) {
        $pdf = new FPDF('L');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 10);

  $pageWidth = $pdf->GetPageWidth();

// Definir a largura total da tabela
$tableWidth = 60 + 19 + 40 + 30 + 18 + 47 + 12 + 36; // Somar larguras das células

// Calcula a posição central horizontal para a tabela
$tableStartX = ($pageWidth - $tableWidth) / 2;
$pdf->SetXY($tableStartX, 10);

  
        // Cabeçalho da tabela
        $pdf->Cell(60, 10, utf8_decode('Nome'), 1,0, 'C', false, '', 14);
        $pdf->Cell(19, 10, utf8_decode('Ação'), 1,0, 'C', false, '', 14);
        $pdf->Cell(40, 10, utf8_decode('Fabricante'), 1,0, 'C', false, '', 14);
        $pdf->Cell(30, 10, utf8_decode('Fornecedor'), 1,0, 'C', false, '', 14);
        $pdf->Cell(18, 10, utf8_decode('Qnt.'), 1,0, 'C', false, '', 14);
        $pdf->Cell(47, 10, utf8_decode('Local'), 1,0, 'C', false, '', 14);
        $pdf->Cell(12, 10, utf8_decode('Lote'), 1,0, 'C', false, '', 14);
        $pdf->Cell(36, 10, utf8_decode('Data'), 1,0, 'C', false, '', 14);
        $pdf->Ln();

        // Conteúdo da tabela
       foreach ($relatorio as $registro) {
    // Posiciona o cursor no centro horizontal da página
    $pdf->SetXY($tableStartX, $pdf->GetY());

            $pdf->Cell(60, 7, ucfirst(Helpers::tirarTraco(Helpers::reduzirTexto($registro->nome,30))), 1);
            $pdf->Cell(19, 7, $registro->acao, 1);
            
        $pdf->Cell(40, 7, ucfirst(Helpers::tirarTraco(Helpers::reduzirTexto($registro->fabricante, 16))), 1);
            $pdf->Cell(30, 7, ucfirst(Helpers::tirarTraco(Helpers::reduzirTexto($registro->fornecedor,12))), 1);
            $pdf->Cell(18, 7, $registro->quantidade, 1);
            $pdf->Cell(47, 7, ucfirst(Helpers::tirarTraco(Helpers::reduzirTexto($registro->local, 24))), 1);
            $pdf->Cell(12, 7, $registro->lote, 1);
            $pdf->Cell(36, 7, $registro->data_hora, 1);
            $pdf->Ln();
        }

        $pdf->Output();
        exit();
    } else {
        // Redirecionar ou mostrar uma mensagem de erro se os dados do relatório não estiverem disponíveis
    }
}

    

    public function erro404(): void {
        echo $this->template->renderizar('error404.html', ['usuario' => 'Leonardo', 'titulo' => 'Página não Encontrada']);
    }

    public function buscarRegistros(): void {
      $produtos = (new Busca())->busca(null,null,'produtos',null,'nome ASC',null);
        $locais = (new Busca())->busca(null,null,'locais',null,'nome ASC',null);
        $buscar = filter_input(INPUT_POST, 'pesquisa', FILTER_DEFAULT);
        if (isset($buscar)) {
            $pesquisas = (new EntradaModelo())->pesquisa($buscar);
           
        }
        echo $this->template->renderizar('buscar.html', ['usuario' => 'Leonardo', 'titulo' => 'Página não Encontrada', 'pesquisas'=>$pesquisas,'produtos'=> $produtos, 'locais'=>$locais]);
    }
    
     public function buscarProdutos(): void {
        
        $buscar = filter_input(INPUT_POST, 'pesquisa', FILTER_DEFAULT);
        if (isset($buscar)) {
            $pesquisas = (new ProdutoModelo())->pesquisa($buscar);
           
        }
        echo $this->template->renderizar('buscarProdutos.html', ['usuario' => 'Leonardo', 'titulo' => 'Página não Encontrada', 'produtos'=>$pesquisas]);
    }
}
