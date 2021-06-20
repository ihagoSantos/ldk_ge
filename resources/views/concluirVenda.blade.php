@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">

        <div class="col-sm-12">
            <div class="titulo-pagina">
                <div class="row">
                    <div class="col-sm-10">
                        <div class="titulo-pagina-nome">
                            <h2>Concluir Venda</h2>
                        </div>
                    </div>
                </div>
            </div><!-- end titulo-pagina -->
        </div><!-- end col-->
    </div><!-- end row-->
   

    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card cardFinalizarPedidos">
                <div class="card-body">
                  <h5 class="card-title">Nome do Cliente</h5>
                  <p class="card-text"><h3>{{$pedido->nomeCliente}}</h3></p>
                </div>
              </div>
        </div>
    
        <div class="col-sm-6">
            <div class="card cardFinalizarPedidos">
                <div class="card-body">
                  <h5 class="card-title">Nome do Funcionário</h5>
                  <p class="card-text"><h3>{{$pedido->nomeFuncionario}}</h3></p>
                </div>
              </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-4">
            <div class="card cardFinalizarPedidos">
                <div class="card-body">
                  <h5 class="card-title">Data da Venda</h5>
                  <p class="card-text"><h3>{{$pedido->created_at->format('d/m/y')}}</h3></p>
                </div>
              </div>
            
        </div>
    
        <div class="col-sm-4">
            <div class="card cardFinalizarPedidos">
                <div class="card-body">
                  <h5 class="card-title">Data de Entrega</h5>
                  <p class="card-text"><h3>{{date('d/m/Y', strtotime($pedido->dataEntrega))}}</h3></p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card cardFinalizarPedidos">
                <div class="card-body">
                  <h5 class="card-title">Valor do Pedido</h5>
                  <p class="card-text"><h3 id="valorDoPedido">R$ {{money_format("%i",$pedido->valorTotal)}}</h3></p>
                </div>
            </div>
        </div>
    </div>


    <form method="POST" action="{{route('concluirVendaComDescontoNosItens')}}">    
        @csrf
        <input type="hidden" name="pedido_id" value="{{$pedido->id}}">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card cardFinalizarPedidos">
                    <div class="card-body">
                    <h5 class="card-title">Itens do Pedido</h5>
                    <p class="card-text">
                        <table id="tabelaItens" class="table table-responsive-lg">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Produto</th>
                                <th scope="col">(R$) Preço/Kg</th>
                                <th scope="col">Peso Solicitado</th>
                                <th scope="col">Valor Total do Item</th>
                                <th scope="col"> Desconto %</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($pedido->itensPedidos as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->nomeProduto}}</td>
                                    <td>{{$item->precoProduto}}</td>
                                    <td>{{$item->pesoFinal}}</td> {{-- Durante o pedido, o peso final é igual ao peso solicitado. Somente na conclusão do pedido que o peso final é atualizado--}}
                                    <td>{{$item->valorReal}}</td>
                                    <td>
                                        {{-- 
                                            Caso, o pedido tenha o status pesado, 
                                            exibe um input para o usuário colocar os valores dos descontos em cada item 
                                        --}}
                                        
                                        <input id="pesoFinal{{$item->id}}" name="desconto[]" value='0' oninput="atualizarValor({{$pedido->valorTotal}})"  step="0.01" type="number" class="form-control" placeholder="Peso Final" disabled>
                                        
                                        @error('pesoFinal{{$item->id}}')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror                                    
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </p>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="row justify-content-center" style="margin:30px 0 30px 0;">
            <div class="col-sm-6" style="heigth:100px">
                <a href="{{route('listarVendas')}}" class="btn btn-secondary-ludke btn-pedido" >Voltar</a>
            </div>
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary-ludke btn-pedido">Concluir Venda</button>
            </div>
        </div>
    </form>
</div>

@endsection

@section('javascript')

<script type="text/javascript">
    // Cria objeto Intl que será responsável por converter os valores para o formato da moeda brasileira
    let formatter = new Intl.NumberFormat([],{
        style: 'currency',
        currency: 'BRL'
    });
    // Valor final do pedido
    let valorDoPedido = $("#valorDoPedido").val();
    
    function validar(){
        alert("Digite o peso do item: ");
    }
    
    function precosItens(){
        let precosItens = []; //array contendo o preço total de cada item
        linhas = $('#tabelaItens>tbody>tr');
        linhas.filter(function(i,elemento){
            valorTotalItem = parseFloat(elemento.cells[4].textContent);
            precosItens.push(valorTotalItem);
        }); 
        return precosItens;
    }


    function valoresInputDesconto(){
        let valoresInputDesconto = [];
        linhas = $('#tabelaItens>tbody>tr'); 
        linhas.filter(function(i,elemento){
            valorDesconto = parseFloat($('#pesoFinal'+elemento.cells[0].textContent).val());
            if(valorDesconto > 100){
                alert("Você não pode aplicar um desconto maior do que 100%");
                $('#pesoFinal'+elemento.cells[0].textContent).val(0);
                valorDesconto = 0;
                valoresInputDesconto.push(valorDesconto);
                return;
            }
            else{
                valoresInputDesconto.push(valorDesconto);

            }
        });

        return valoresInputDesconto;
    }
    function calcularDesconto(arrayPrecosItens,arrayValoresInputDesconto){
        let desconto = 0.0;
        for(i = 0; i<arrayPrecosItens.length;i++){
            desconto += arrayPrecosItens[i] * (arrayValoresInputDesconto[i]/100);
        }
        // console.log(`Desconto: ${desconto}`)
        return desconto;
    }
    function atualizarValor(pedidoValorTotal){
        // Inicializo Valores
        let arrayPrecosItens = precosItens();
        let arrayValoresInputDesconto = valoresInputDesconto();
        let valorDescontoTotal = 0.0; 
        // Calcular descontos
        valorDescontoTotal = calcularDesconto(arrayPrecosItens,arrayValoresInputDesconto);
        let valorDoPedido = pedidoValorTotal;

        $("#valorDoPedido").html(formatter.format(valorDoPedido - valorDescontoTotal));
    }
    
</script>    
@endsection