@extends('layouts.app')

@section('content')
{{--
    VIEW PESAGEM DO PEDIDO. NESSA TELA, É INFORMADO O PESO DE CADA ITEM INDIVIDUALMENTE
--}}
<div class="container">
    <div class="row justify-content-center">

        <div class="col-sm-12">
            <div class="titulo-pagina">
                <div class="row">
                    <div class="col-sm-10">
                        <div class="titulo-pagina-nome">
                            <h2>Pesagem do Pedido</h2>
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
                  <h5 class="card-title">Data do Pedido</h5>
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
                  <p class="card-text"><h3 id="valorDoPedido"></h3></p>
                </div>
            </div>
        </div>
    </div>


    <form method="POST" action="{{route('pedido.concluirPedidoPesoFinal')}}">
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
                                <th scope="col">(KG) Peso Solicitado</th>
                                <th scope="col">(R$) Valor Estimado</th>
                                <th scope="col">(KG) Peso Final</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($pedido->itensPedidos as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->nomeProduto}}</td>
                                    <td>{{money_format("%i",$item->precoProduto)}}</td>
                                    <td>{{$item->pesoFinal}}</td> {{-- Durante o pedido, o peso final é igual ao peso solicitado. Somente na conclusão do pedido que o peso final é atualizado--}}
                                    <td>{{money_format("%i",$item->valorReal)}}</td>
                                    <td>
                                        <input id="pesoFinal{{$item->id}}" oninput="atualizarValor({{$item->precoProduto}},{{$item->id}})" name="pesoFinal{{$item->id}}" step="0.01" type="number" class="form-control" placeholder="Peso Final" required>

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
                <?php //dd($pedido)?>

            </div>
        </div>

        <div class="row justify-content-center" style="margin:30px 0 30px 0;">
            <div class="col-sm-6" style="heigth:100px">
                <a href="{{route('listarPedidos')}}" class="btn btn-secondary-ludke btn-pedido" >Voltar</a>
            </div>
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary-ludke btn-pedido">Finalizar Pesagem</button>
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

    function validar(){
        alert("Digite o peso do item: ");
    }
    // Valor final do pedido
    var valorDoPedido = 0.0;
    $("#valorDoPedido").html(formatter.format(valorDoPedido));

    var valores = {};

    function atualizarValor(precoProduto,id){
        valor = 0.0;

        linhas = $('#tabelaItens>tbody>tr');
        linhas.filter(function(i,elemento){

            valorInput = $('#pesoFinal'+elemento.cells[0].textContent).val();
            if(valorInput){
                valor += parseFloat(valorInput) * parseFloat(elemento.cells[2].textContent);
            }

        });
        $("#valorDoPedido").html(formatter.format(valor));
    }

    // console.log(e[0].cells[1].textContent)
</script>
@endsection
