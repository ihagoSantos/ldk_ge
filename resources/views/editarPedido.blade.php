@extends('layouts.app')

@section('content')

<div id="conteudo-pedidos" class="container-fluid">

    <div class="row justify-content-center">
        {{-- Coluna 1 --}}
        <div class="col-sm-6">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="titulo-pagina">
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="titulo-pagina-nome">
                                <h2>Editar Pedido {{'#'.$pedido->id}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="cardCliente" class="card card-pedidos">
                        {{-- <div class="card-header">Cliente</div> --}}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Cliente</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3>{{$pedido->nomeCliente}}</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Funcionário Responsável</label>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3>{{$pedido->nomeFuncionario}}</h3>

                                </div>
                            </div>
                        </div>

                    </div>{{-- end Card Cliente --}}
                </div>
            </div>
            {{-- Row Produto --}}
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    {{-- Card Produto --}}
                    <div id="cardProduto" class="card card-pedidos">
                        <div class="card-header">Produto</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input id="buscaProduto" type="text" class="form-control" placeholder="Nome do Produto">
                                                {{-- lista de produtos retornados da busca--}}
                                                <ul id="resultadoBuscaProduto" class="list-group"></ul>
                                            </div>
                                            <div class="col-sm-2">
                                                <input id="pesoProduto" step="0.01" type="number" class="form-control" placeholder="Peso">
                                            </div>
                                            <div class="col-sm-4">
                                                <a href="#" id="adicionarProduto" class="btn btn-primary-ludke">Adicionar</a>
                                            </div>
                                        </div>
                                        </div>
                                </div>
                            </div>

                            {{-- informações do produto --}}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>Nome</label>
                                            <input type="hidden" id="idProduto">
                                            <h4 id="nomeProduto"></h4>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="hidden" id="precoProduto">
                                            <label for="">Preço/Kg (R$)</label>
                                            <h4 id="textoPrecoProduto"></h4>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="">Preço Estimado (R$)</label>
                                            <h4 id="precoEstimado"></h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Descrição</label>
                                            <h5 id="descricaoProduto"></h5>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Categoria</label>
                                            <h5 id="categoriaProduto"></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>{{-- informações do produto --}}
                        </div>
                    </div>{{-- end Card Produto --}}
                </div>
            </div>{{-- end Row Produto --}}

           {{-- <div class="row">
                <div class="col-sm-6">
                    <a href="{{route('listarPedidos')}}" class="btn btn-secondary-ludke btn-pedido">Cancelar Edição</a>
                </div>
                <div class="col-sm-6">
                    <a href="#" id="btnFinalizarPedido" class="btn btn-primary-ludke btn-pedido">Finalizar Edição</a>
                </div>
            </div>--}}

        </div>{{-- end Coluna 1 --}}

        {{-- Coluna 2 --}}

        <div class="col-sm-6">
            <div class="row justify-content-center">
                <div class="col-sm-12">

                    <div class="card card-pedidos">
                        <div class="card-header">Pedido </div>
                        <div id="listaPedidos" class="card-body">
                            <table id="tabelaPedidos" class="table table-responsive-lg table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>COD</th>
                                        <th>NOME</th>
                                        <th>PESO</th>
                                        <th>PREÇO/KG</th>
                                        <th>VALOR TOTAL</th>
                                        <th>AÇÕES</th>

                                    </tr>
                                </thead>
                                <tbody >
                                    {{-- VALORES DA TABELA SÃO DINAMICOS --}}
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>{{--  end lista produtos--}}

            </div>

            {{-- Row Informações Venda --}}
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    {{-- Card Venda --}}
                    <div id="card-venda" class="card card-pedidos">
                        <div class="card-header">Dados do Pedido</div>
                        <div class="card-body">
                            {{-- informações do produto --}}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row justify-content-between">
                                        <div class="col-sm-4">
                                            <label for="">Número de Itens</label>
                                            <h4 id="qtdItens"></h4>



                                              <label>Data de Entrega</label>
                                            <div class="input-group">
                                                <input id="inputDataEntrega" value="{{$pedido->dataEntrega}}" type="date" class="form-control">

                                            </div>
                                        </div>

                                        <div class="col-sm-5">
                                            <label for="">Total</label>
                                            <h1 id="valorTotal" value=""></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>{{-- informações do produto --}}
                        </div>
                    </div>{{-- end Card Venda --}}
                </div>
            </div>{{-- Row Informações Venda --}}
            <div class="row">
                <div class="col-sm-6">
                    <a href="{{route('listarPedidos')}}" class="btn btn-secondary-ludke btn-pedido">Cancelar Edição</a>
                </div>
                <div class="col-sm-6">
                    <a href="#" id="btnFinalizarPedido" class="btn btn-primary-ludke btn-pedido">Finalizar Edição</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
    // Cria objeto Intl que será responsável por converter os valores para o formato da moeda brasileira
    let formatter = new Intl.NumberFormat([],{
        style: 'currency',
        currency: 'BRL'
    });
    // Objeto contendo as informações do pedido
    var pedido = <?php echo $pedido ?>;
    pedido.deletar = [];
    pedido.listaProdutos = [];
    console.log(pedido);
    var cont = 1;

    function carregaProdutos(){
        // Carrega os produtos na tela
        for(let i = 0; i < pedido.itens_pedidos.length; i++){
            console.log(pedido.itens_pedidos[i].id);
            linha = "<tr>"+
                        "<td><strong>"+(cont)+"</strong></td>"+
                        "<td value="+pedido.itens_pedidos[i].id+">"+pedido.itens_pedidos[i].id+"</td>"+
                        "<td>"+pedido.itens_pedidos[i].nomeProduto+"</td>"+
                        "<td value="+pedido.itens_pedidos[i].pesoSolicitado+">"+pedido.itens_pedidos[i].pesoSolicitado+"</td>"+
                        "<td>"+formatter.format(pedido.itens_pedidos[i].precoProduto)+"</td>"+
                        "<td value="+pedido.itens_pedidos[i].valorReal+" class="+"precoCalculado"+">"+formatter.format(pedido.itens_pedidos[i].valorReal)+"</td>"+
                        "<td><a href="+"#"+" onclick="+"removerProduto("+cont+")"+">"+
                            "<img id="+"iconeDelete"+" class="+"icone"+" src="+"{{asset('img/trash-alt-solid.svg')}}"+" style="+"width:18px"+">"+
                        "</a></td>"+
                    "</tr>";

            // montarLinha(pedido.itens_pedidos[i],pedido.itens_pedidos[i].pesoSolicitado)
            $("#tabelaPedidos>tbody").append(linha);
            cont += 1;
        }
        // Atualiza o numero de itens
        $("#qtdItens").html(pedido.itens_pedidos.length);
        // console.log(pedido.itens_pedidos.length);

        // Atualiza o valor total estimado do pedido
        // subtotal = calcularSubtotal();
        // $("#subtotal").html(subtotal);

        // Calcula o desconto
        // desconto = calcularDesconto();
        // $("#ValorDesconto").html(desconto);

        // Calcula o total
        total = calcularTotal();
        pedido.total = total;
        // console.log(pedido);
        $("#valorTotal").html(formatter.format(total));
        $("#valorTotal").val(total);
    }


    $(function(){
        // Configuração do ajax com token csrf
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        carregaProdutos();
        // Valor Total e num itens
        // $("#valorTotal").html(0);
        $("#subtotal").html(0);
        $("#ValorDesconto").html(0);
        // $("#qtdItens").html(0);

        // Busca do Produto
        $('#buscaProduto').keyup(function(){
            var buscaProduto = $(this).val().toUpperCase();
            if(buscaProduto.length >= 3){
                getProdutos(buscaProduto);
            }
            if(buscaProduto.length == 0){
                limparCamposProduto();
            }
            else{
                $('#resultadoBuscaProduto').children().remove();
            }
        });

        // Clicar no link dos produtos
        $('body').on('click', "#resultadoBuscaProduto a", function(){
            idProduto = $(this).children().val(); //id do produto
            buscaProduto(idProduto);
        });

        // Digitar o valor do produto
        $('#pesoProduto').keyup(function(){
            if($(this).val() >= 0){
                $("#precoEstimado").html(formatter.format(calcularPrecoProduto($(this).val())));
            }else{
                alert("Esse peso não pode ser calculado");
                $(this).val(0);
                return;
            }
        });

        // Adicionar Produto à lista
        $("#adicionarProduto").click(function(){
            if($("#idProduto").val()){
                adicionarProduto($("#idProduto").val());
            }else{
                alert("Erro ao adicionar Produto");
                return;
            }
        });

        //Digitar desconto
        $("#inputDesconto").keyup(function(){
            calcularDesconto();
            total = calcularTotal();
            $("#valorTotal").html(total);
            $("#valorTotal").val(total);
        });
        // Finalizar Pedido
        $("#btnFinalizarPedido").click(function(){
            confirma = confirm("Você deseja finalizar a edição do pedido?");
            if(confirma){
                montarPedido();
            }
        });
    });

    function getProdutos(buscaProduto){

        $.ajax({
            type: "POST",
            url: "/pedidos/getProdutos",
            data: {nome: buscaProduto},
            context: this,
            success: function(data){
                produtos = JSON.parse(data)
                // console.log(produtos)

                // limpa os links da lista com os produtos retornados em tempo real
                $('#resultadoBuscaProduto').children().remove();
                for(let i = 0; i < produtos.length; i++){

                    let linha = "<a "+"href="+"#"+">"+
                                    "<li value="+produtos[i].id+" class="+"list-group-item itemLista"+">"+produtos[i].nome+"</li>"+
                                "</a>";
                    $('#resultadoBuscaProduto').append(linha);
                }

            },
            error: function(error){
                console.log(error);
            }
        });
    }

    // Busca Produto selecionado no banco
    function buscaProduto(id){
        $.ajax({
            url:'/produtos/'+id,
            method:"GET",
            success: function(data){
                produto = JSON.parse(data);
                // console.log(produto);
                // console.log(produto.nome);
                // console.log(produto.preco);
                $("#idProduto").val(produto.id);
                $("#nomeProduto").html(produto.nome);
                $("#buscaProduto").val(produto.nome);
                $("#textoPrecoProduto").html(formatter.format(produto.preco));
                $("#precoProduto").val(produto.preco);
                $("#descricaoProduto").html(produto.descricao);
                $("#categoriaProduto").html(produto.categoria.nome);

                // limpa os links da lista com os produtos retornados em tempo real
                $('#resultadoBuscaProduto').children().remove();
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    // calcula o valor total do item adicionado a lista de pedidos
    function calcularTotalItem(valorProduto, peso){
        return valorProduto*peso;
    }
    function adicionarProduto(id){

        pesoProduto = parseFloat($("#pesoProduto").val());
        if(pesoProduto && pesoProduto>0){
            $.getJSON('/produtos/'+id,function(data){
                produto = data;
                // console.log("adicionarProduto()",produto)
                if(produto){
                        // Adiciona as informações do produto à lista de pedidos
                        // let itemPedido  = [];
                        // itemPedido.push({
                        //     produto_id: produto.id,
                        //     peso:peso,
                        //     valorTotalItem: calcularTotalItem(produto.preco,peso)
                        //     });
                        pedido.listaProdutos.push({
                            produto_id: produto.id,
                            peso:pesoProduto,
                            valorTotalItem: calcularTotalItem(produto.preco,pesoProduto)
                            });
                        console.log("LISTA PRODUTOS",pedido.listaProdutos)
                        // console.log("adicionarProduto()",pedido)


                        // Adiciona linha à tabela
                        linha = montarLinha(produto,pesoProduto);
                        $("#tabelaPedidos>tbody").append(linha);
                        cont += 1;

                        // Atualiza o numero de itens
                        $("#qtdItens").html(pedido.listaProdutos.length);

                        // Calcula o total
                        total = calcularTotal();
                        console.log(total);
                        pedido.total = total;
                        console.log(pedido);
                        $("#valorTotal").html(formatter.format(total));
                        $("#valorTotal").val(total);


                        // console.log()
                        limparCamposProduto();

                }
            });
        }else{
            alert("Digite o peso do produto!");
        }
    }
    function montarLinha(produto,peso){
        linha = "<tr>"+
                    "<td><strong>"+(cont)+"</strong></td>"+
                    "<td value="+produto.id+">"+produto.id+"</td>"+
                    "<td>"+produto.nome+"</td>"+
                    "<td value="+peso+">"+peso+"</td>"+
                    "<td>"+formatter.format(produto.preco)+"</td>"+
                    "<td value="+calcularTotalItem(produto.preco,peso)+" class="+"precoCalculado"+">"+formatter.format(calcularTotalItem(produto.preco,peso))+"</td>"+
                    "<td><a href="+"#"+" onclick="+"removerProduto("+cont+")"+">"+
                        "<img id="+"iconeDelete"+" class="+"icone"+" src="+"{{asset('img/trash-alt-solid.svg')}}"+" style="+"width:18px"+">"+
                    "</a></td>"+
                "</tr>";
        return linha;
    }
    function removerProduto(idLinha){
        console.log("Remover Produto: ",idLinha);
        linhas = $("#tabelaPedidos>tbody>tr");
        // retorna a linha do produto a ser removido
        e = linhas.filter(function(i,elemento){
            return elemento.cells[0].textContent == idLinha;
        });
        if(e){
            // console.log(e.length)
            idProduto = parseInt(e[0].cells[1].textContent);
            peso = parseFloat(e[0].cells[3].textContent);
            valorTotal = (e[0].cells[5].textContent);

            // Remove Pedidos vindo do banco
            for(var i = 0; i < pedido.itens_pedidos.length; i++){
                // Verifica se o id do produto, o peso solicitado e o valor total é igual à linha que estou tententando remover
                if( pedido.itens_pedidos[i].id == idProduto && pedido.itens_pedidos[i].pesoSolicitado == peso && formatter.format(pedido.itens_pedidos[i].valorReal) == valorTotal){

                    var indice = pedido.itens_pedidos.indexOf(pedido.itens_pedidos[i]);
                    // Debita Valor do Pedido
                    pedido.valorTotal = debitarValor(pedido.itens_pedidos[i].valorReal);
                    pedido.deletar.push({id:parseInt(pedido.itens_pedidos[i].id),peso:parseFloat(pedido.itens_pedidos[i].pesoSolicitado),valorReal:parseFloat(pedido.itens_pedidos[i].valorReal)});
                    console.log(parseInt(pedido.itens_pedidos[i].id));
                    pedido.itens_pedidos.splice(indice,1)
                    e.remove();

                    // Atualiza o numero de itens
                    $("#qtdItens").html(pedido.itens_pedidos.length);

                    // Calcula o total
                    total = calcularTotal();
                    pedido.total = total;
                    // console.log(pedido);
                    $("#valorTotal").html(formatter.format(total));
                    $("#valorTotal").val(total);
                }
            }
            // Remove pedidos adicionados durante a edição
            for(var i = 0; i < pedido.listaProdutos.length; i++){
                // verifica se o id do produto, peso e valor é igual a linha que desejo remover
                if( pedido.listaProdutos[i].produto_id == idProduto && pedido.listaProdutos[i].peso == peso && formatter.format(pedido.listaProdutos[i].valorTotalItem) == valorTotal){
                    console.log(pedido.listaProdutos[i].produto_id, pedido.listaProdutos[i].peso, pedido.listaProdutos[i].valorTotalItem)
                    var indice = pedido.listaProdutos.indexOf(pedido.listaProdutos[i]);
                    // Debita Valor do Pedido
                    pedido.valorTotal = debitarValor(pedido.listaProdutos[i].valorTotalItem);
                    console.log("INDICE",indice)
                    pedido.listaProdutos.splice(indice,1)
                    e.remove();

                    // Atualiza o numero de itens
                    $("#qtdItens").html(pedido.listaProdutos.length);

                    // Calcula o total
                    total = calcularTotal();
                    pedido.total = total;
                    // console.log(pedido);
                    $("#valorTotal").html(total);
                    $("#valorTotal").val(total);
                }
            }
            console.log(pedido)
        }
    }

    function limparCamposProduto(){

        $("#idProduto").val('');
        $("#buscaProduto").val('');
        $("#pesoProduto").val('');

        $("#nomeProduto").html('');
        $("#textoPrecoProduto").html('');
        $("#precoEstimado").html('');
        $("#descricaoProduto").html('');
        $("#categoriaProduto").html('');
    }
    function debitarValor(preco){
        return parseFloat(pedido.valorTotal) - preco;
    }
    function calcularDesconto(){
        // valor do desconto
        let desconto = 0;
        desconto = $("#inputDesconto").val()

        // console.log("calcularDesconto()",desconto)

        // subtotal = calcularSubtotal();

        // resultado = (subtotal * (desconto/100)).toPrecision();

        $("#ValorDesconto").html(resultado);
        return resultado;

    }
    function calcularTotal(){
        // percorre a lista de produtos calculando o subtotal
        var total = 0;
        var listaProdutos = pedido.itens_pedidos;
        for(i = 0; i < listaProdutos.length; i++){
            total += parseFloat(listaProdutos[i].valorReal);
        }
        console.log("TOTAL: ",total)
        for(i = 0; i < pedido.listaProdutos.length; i++){
            total += parseFloat(pedido.listaProdutos[i].valorTotalItem)
        }

        return total;

    }
    function calcularPrecoProduto(peso){
        if(peso>0){
            preco = $("#precoProduto").val();
            resultado = preco * peso;
            return resultado;
        }

    }
    function limparTela(){

    }
    function montarPedido(){


        pedido.desconto = 0;

        pedido.valorDesconto = 0;
        pedido.dataEntrega = $("#inputDataEntrega").val();

        if(!pedido.cliente_id){
            alert("Selecione o cliente para concluir o pedido!");
            return;
        }
        if(pedido.itens_pedidos.length == 0 && pedido.total == 0){
            alert("Selecione um ou mais produtos para concluir o pedido!");
            return;
        }
        if(pedido.dataEntrega.length == 0){
            alert("Selecione uma data de entrega para concluir o pedido!");
            return;
        }
        else{

            console.log(pedido)
            $.ajax({
                url: '/pedidos/update/'+pedido.id,
                method: "PUT",
                data: pedido,
                context: this,
                success: function(data){
                    console.log("Sucesso!!!")
                    // window.location.href = '/pedidos/listar';
                    window.location.href = '/pedidos/listar';
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
    }
</script>
@endsection
