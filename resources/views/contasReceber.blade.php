@extends('layouts.app')

@section('content')

<div class="container">
    {{-- Titulo --}}
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="titulo-pagina">
                <div class="row">
                    <div class="col-md-6">
                        <div class="titulo-pagina-nome">
                            <h2>Contas a receber</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end col-->
    </div><!-- end row-->

    @if ($listarTodos == true)
        <div class="row">
            <div class="col-sm-12 limparBusca">
                <a href="{{route('contas.receber')}}">
                    <button class="btn btn-outline-danger">Listar Todos</button>
                </a>

            </div>
        </div>
    @endif

    {{-- Nav-tabs --}}
    <ul class="nav nav-tabs" id="myTabContas" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link-contas active" id="aguardando-tab" data-toggle="tab" href="#aguardando" role="tab" aria-controls="aguardando" aria-selected="true"><h5>Aguardando Pagamento</h5></a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link-contas" id="pago-tab" data-toggle="tab" href="#pago" role="tab" aria-controls="pago" aria-selected="false"><h5>Pago</h5></a>
        </li>
    </ul>
      {{-- Tab Content --}}
      <div class="tab-content" id="myTabContent">
          {{-- Aguardando Pagamento --}}
        <div class="tab-pane fade show active" id="aguardando" role="tabpanel" aria-labelledby="aguardando-tab">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <table id="tabelaCargos" class="table table-hover table-responsive-md">
                        <thead class="thead-primary">
                        <tr>
                            <th>#PAG.</th>
                            <th>#PED.</th>
                            <th>Cliente</th>
                            <th>Valor Total</th>
                            <th>Valor Pago</th>
                            <th>Data de Vencimento</th>
                            <th>Funcionário</th>
                            <th>Situação</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($pagamentosAbertos as $pagamento)
                                <tr>
                                    <td>{{$pagamento->id}}</td>
                                    <td>{{$pagamento->pedido->id}}</td>
                                    <td>
                                        @if(isset($pagamento->pedido->cliente->user))
                                            {{$pagamento->pedido->cliente->user->name}}
                                        @else
                                            <?php $cliente = \App\Cliente::withTrashed()->find($pagamento->pedido->cliente_id);
                                            $cliente->user_id;
                                            $user = \App\User::withTrashed()->find($cliente->user_id);
                                            ?>
                                            {{$user->name}}
                                        @endif



                                    </td>
                                    <td>R$ {{money_format('%i',$pagamento->valorTotalPagamento - ($pagamento->descontoPagamento/100))}}</td>
                                    {{-- Calcula o total pago somando o pagamento dos pedidos que possui status fechado --}}
                                    <td>R$ {{money_format('%i',$pagamento->valorPago)}}</td>
                                    <td>
                                        {{date('d/m/Y',strtotime($pagamento->dataVencimento))}}
                                    </td>
                                    <td>
                                        {{$pagamento->funcionario->user->name}}
                                    </td>
                                    <td>
                                        {{-- Pagamento Vencido --}}
                                        @if(date($pagamento->dataVencimento) < date('Y-m-d') && $pagamento->valorPago == 0)
                                            <div class="statusVencido" title="Pagamento Vencido"></div>
                                        {{-- Aguardando Pagamento --}}
                                        @elseif(date($pagamento->dataVencimento) >= date('Y-m-d') && $pagamento->valorPago == 0)
                                            <div class="statusAguardando" title="Aguardando Pagamento"></div>
                                        @else
                                        {{-- Pago --}}
                                            <div class="statusPago" title="Pago"></div>
                                        @endif

                                    </td>
                                    <td>
                                        @if ($pagamento->valorPago == 0 || $pagamento->valorPago == null)
                                        {{-- Pagamento --}}
                                        <a id="registrarPagamento" title="Registrar pagamento" onclick="registrarPagamento({{$pagamento->id}})">
                                            <img id="pagar" class="icone" src="{{asset('img/money-bill-wave-solid.svg')}}" >
                                        </a>
                                        @else
                                        {{-- Visualizar --}}
                                        <a id="visualizarPagamento" title="Visualizar pagamento" onclick="exibir({{$pagamento->id}})">
                                            <img class="icone" src="{{asset('img/eye-solid.svg')}}" >
                                        </a>
                                        @endif

                                        {{-- Editar Pagamento --}}
                                        <a id="editarPagamento" title="Editar Pagamento" href="{{route('contas.editarPagamento',['id'=>$pagamento->id])}}">
                                            <img id="vPedido" class="icone" style="width: 20px" src="{{asset('img/edit-solid.svg')}}" >
                                        </a>

                                        {{-- Redirecionar para pedidos ou vendas --}}
                                        @if ($pagamento->pedido->tipo == 'p')
                                            <a id="visualizarPedido" title="Visualizar Pedido" href="{{route('contas.visualizarPedido',['id'=>$pagamento->pedido->id])}}" target="_blank">
                                                <img id="vPedido" class="icone" style="width: 20px" src="{{asset('img/clipboard-list-solid.svg')}}" >
                                            </a>
                                        @else
                                            <a id="visualizarVenda" title="Visualizar Venda" href="{{route('contas.visualizarVenda',['id'=>$pagamento->pedido->id])}}" target="_blank">
                                                <img id="vVenda" class="icone" style="width: 20px" src="{{asset('img/clipboard-list-solid.svg')}}" >
                                            </a>
                                        @endif

                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Paginate --}}
            <div class="row justify-content-center">
                @if ($pagamentosAbertos != [])
                    @if (isset($filtro))
                    {{ $pagamentosAbertos->appends($filtro)->links() }}

                    @else
                    {{ $pagamentosAbertos->links() }}
                    @endif
                @endif
            </div>
        </div>
        {{-- Pago --}}
        <div class="tab-pane fade" id="pago" role="tabpanel" aria-labelledby="pago-tab">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <table id="tabelaCargos" class="table table-hover table-responsive-md">
                        <thead class="thead-primary">
                        <tr>
                            <th>#PAG.</th>
                            <th>#PED.</th>
                            <th>Cliente</th>
                            <th>Valor Total</th>
                            <th>Valor Pago</th>
                            <th>Data de Vencimento</th>
                            <th>Data de Pagamento</th>
                            <th>Funcionário</th>
                            <th>Situação</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($pagamentosFechados as $pagamento)
                                <tr>
                                    <td>{{$pagamento->id}}</td>
                                    <td>{{$pagamento->pedido->id}}</td>
                                    <td>
                                        @if(isset($pagamento->pedido->cliente->user))
                                            {{$pagamento->pedido->cliente->user->name}}
                                        @else
                                            <?php $cliente = \App\Cliente::withTrashed()->find($pagamento->pedido->cliente_id);
                                            $cliente->user_id;
                                            $user = \App\User::withTrashed()->find($cliente->user_id);
                                            ?>
                                            {{$user->name}}
                                        @endif</td>
                                    <td>R$ {{money_format('%i',$pagamento->valorTotalPagamento - ($pagamento->descontoPagamento/100))}}</td>
                                    {{-- Calcula o total pago somando o pagamento dos pedidos que possui status fechado --}}
                                    <td>R$ {{money_format('%i',$pagamento->valorPago)}}</td>
                                    <td>
                                        {{date('d/m/Y',strtotime($pagamento->dataVencimento))}}
                                    </td>
                                    <td>
                                        {{date('d/m/Y',strtotime($pagamento->dataPagamento))}}
                                    </td>
                                    <td>
                                        {{$pagamento->funcionario->user->name}}
                                    </td>
                                    <td>
                                        {{-- Pago --}}
                                        <div class="statusPago" title="Pago"></div>
                                    </td>
                                    <td>
                                        {{-- Visualizar --}}
                                        <a id="visualizarPagamento" title="Visualizar pagamento" onclick="exibir({{$pagamento->id}})">
                                            <img class="icone" src="{{asset('img/eye-solid.svg')}}" >
                                        </a>
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Paginate --}}
            <div class="row justify-content-center">
                @if ($pagamentosFechados != [])
                    @if (isset($filtro))
                    {{ $pagamentosFechados->appends($filtro)->links() }}

                    @else
                    {{ $pagamentosFechados->links() }}
                    @endif
                @endif
            </div>
        </div>

      </div>




    {{-- Titulo --}}
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="titulo-pagina">
                <div class="row">
                    <div class="col-md-12">
                        <div class="titulo-pagina-nome">
                            <h2>Resumo Mensal - {{date('d/m/Y',mktime(0,0,0, date('m'), 1, date('Y')))}} à {{date('t/m/Y')}}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end col-->
    </div><!-- end row-->

    {{-- cards info --}}
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Total Contas</h5>
                  <h1 class="card-text">{{$infoMensal['totalPagamentos']}}</h1>
                </div>
              </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Valor Total Recebido</h5>
                    <h1 class="card-text">R$ {{money_format('%i',$infoMensal['valorTotalPago'])}}</h1>
                </div>
              </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Valor Total a Receber</h5>
                  <h1 class="card-text">R$ {{money_format('%i',$infoMensal['valorTotalAguardando'])}}</h1>
                </div>
              </div>
        </div>
    </div>

    {{-- End Cards Info --}}

    <!-- Modal Registrar Pagamento -->
    <div class="modal fade" id="modalRegistrarPagamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Registrar Pagamento</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="formRegistrarPagamento" action="{{route('contas.registrarPagamento')}}" method="POST">
                @csrf
                <input type="hidden" id="formIdPagamento" name="formIdPagamento">
                <input type="hidden" id="formIdPedido" name="formIdPedido">
                <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="">Cliente</label>
                        <h4 id="nomeCliente"></h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <label for="">Data de Vencimento</label>
                        <h4 id="dataVencimento"></h4>
                    </div>
                    <div class="col-sm-4">
                        <label for="">Tipo</label>
                        <h4 id="tipo"></h4>
                    </div>
                    <div class="col-sm-4">
                        <label for="">Situação</label>
                        <h4 id="situacao"></h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <label for="">Desconto</label>
                        <h4 id="desconto"></h4>
                    </div>
                    <div class="col-sm-4">
                        <label for="">Valor com Desconto</label>
                        <h4 id="valorComDesconto"></h4>
                    </div>
                    <div class="col-sm-4">
                        {{-- <h4 id="valorTotal"></h4> --}}
                        <div class="form-group">
                            <label for="">Valor Total (R$)</label>
                            <input type="number" step="0.01" class="form-control" id="formValorPago" name="formValorPago" required>
                        </div>
                    </div>
                </div>

                <div class="row" id="vencimentoNovoPagamento"></div>
            </div>

            <input type="hidden" id="valorTotalPagamento" name="valorTotalPagamento">

            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Registrar Pagamento</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </form>
        </div>
    </div>
    </div>

      <!-- Modal Visualizar Pagamento -->
    <div class="modal fade" id="modalVisualizarPagamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Visualizar Pagamento</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

            <div class="row">
                <div class="col-sm-3">
                    <label for="">Cliente</label>
                    <h4 id="nomeClienteVizualizar"></h4>
                </div>
                  <div class="col-sm-3">
                    <label for="">Data de Vencimento</label>
                    <h4 id="dataVencimentoVizualizar"></h4>
                  </div>
                  <div class="col-sm-3">
                    <label for="">Tipo</label>
                    <h4 id="tipoVizualizar"></h4>
                  </div>
                  <div class="col-sm-3">
                    <label for="">Situação</label>
                    <h4 id="situacaoVizualizar"></h4>
                  </div>
              </div>

                <div class="row">
                    <div class="col-sm-3">
                    <label for="">Valor Total</label>
                    <h4 id="valorTotalVizualizar"></h4>
                    </div>
                    <div class="col-sm-3">
                    <label for="">Desconto</label>
                    <h4 id="descontoVizualizar"></h4>
                    </div>
                    <div class="col-sm-3">
                    <label for="">Valor com Desconto</label>
                    <h4 id="valorComDescontoVizualizar"></h4>
                    </div>
                    <div class="col-sm-3">
                    <label for="">Valor Pago</label>
                    <h4 id="valorPagoVizualizar"></h4>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>

          </div>
        </div>
      </div>
</div>{{-- End Container --}}



@endsection

@section('javascript')
<script type="text/javascript">

$(document).ready(function(){
    // Validação ao submeter o formulário de pagamento
    $("#formRegistrarPagamento").submit(function(event){
        // Valor total do pagamento
        let valorTotalPagamento = $("#valorTotalPagamento").val();
        // Valor informado no input
        let formValorPago = $("#formValorPago").val();

        if(formValorPago > valorTotalPagamento){
            event.preventDefault();
            alert("O valor informado é maior do que o valor do pagamento. Informe o valor corretamente.");
        }
        /**
            Verifica se o valor informado é menor do que o valor do pedido. Caso seja, insere um input
            de data para o usuário informar a data de vencimento do próximo pagamento
        */
        else if(formValorPago < valorTotalPagamento && $("#vencimentoNovoPagamento").html() == ""){
            event.preventDefault();
            inputDate = `<div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-danger" role="alert">
                                O valor informado é menor do que o valor do pagamento. Devido a isso, um novo pagamento será criado
                                com o valor restante. <strong>Por favor, informe a data de vencimento do novo pagamento.</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Data de Vencimento</label>
                            <input type="date" class="form-control" id="dataVencimentoNovoPagamento" name="dataVencimentoNovoPagamento" min="{{Date('Y-m-d')}}" required>
                        </div>
                    </div>`;
            $("#vencimentoNovoPagamento").html(inputDate)
        }
    });

});
function exibir(idPagamento){
    // Cria objeto Intl que será responsável por converter os valores para o formato da moeda brasileira
    let formatter = new Intl.NumberFormat([],{
        style: 'currency',
        currency: 'BRL'
    });

    // limpa modal
    limpaModalVisualizarPagamento();

    $.ajax({
        type:"GET",
        url: "/contas/visualizar/"+idPagamento,
        context:this,
        success: function(pagamento){
            pagamento = JSON.parse(pagamento);
            // seta id Pagamento

            // Nome do Cliente
            $("#nomeClienteVizualizar").html(pagamento.pedido.cliente.user.name);

            // Data de vencimento
            if(pagamento.dataVencimento != null){
                let arrayDataVencimento = pagamento.dataVencimento.split('-');
                let dataVencimento = arrayDataVencimento[2] + "/" + arrayDataVencimento[1] + "/" +arrayDataVencimento[0];
                $("#dataVencimentoVizualizar").html(dataVencimento);

            }


            // Tipo de Pedido
            if(pagamento.pedido.tipo == 'p')
                $("#tipoVizualizar").html("Pedido");
            else if(pagamento.pedido.tipo == 'v')
                $("#tipoVizualizar").html("Venda");
            else
            $("#tipoVizualizar").html("Venda Mobile");

            // Situação
            let estaVencido = new Date(pagamento.dataVencimento).getTime() < new Date().getTime();

            // vencido
            if(estaVencido && pagamento.valorPago == 0)
                $("#situacaoVizualizar").html("Pagamento Vencido");
            // aguardando pagamento
            else if(!estaVencido && pagamento.valorPago == 0)
                $("#situacaoVizualizar").html("Aguardando Pagamento");
            // pago
            else
                $("#situacaoVizualizar").html("Pago");


            // Valor Total
            let valorTotal = pagamento.valorTotalPagamento;
            $("#valorTotalVizualizar").html(formatter.format(valorTotal));

            // Desconto
            let desconto = valorTotal * (pagamento.descontoPagamento / 100);
            $("#descontoVizualizar").html(formatter.format(desconto));

            // Valor Com Desconto
            let valorComDesconto = valorTotal - desconto;
            // console.log(valorComDesconto)
            $("#valorComDescontoVizualizar").html(formatter.format(valorComDesconto));

            // Seta valor pago no form
            $("#valorPagoVizualizar").html(formatter.format(valorComDesconto));
        }
    });
    $("#modalVisualizarPagamento").modal('show');
}

function limpaModalRegistroPagamento(){
    // limpa textos
    $("#nomeCliente").html("");
    $("#dataVencimento").html("");
    $("#tipo").html("");
    $("#situacao").html("");
    $("#valorTotal").html("");
    $("#desconto").html("");
    $("#valorComDesconto").html("");
    $("#valorPago").html("");
    $("#vencimentoNovoPagamento").html("");
    // limpa id
    $("#formIdPagamento").val("");
    $("#formIdPedido").val("");
    $("#formValorPago").val("");

}

function limpaModalVisualizarPagamento(){
    // limpa textos
    $("#nomeClienteVizualizar").html("");
    $("#dataVencimentoVizualizar").html("");
    $("#tipoVizualizar").html("");
    $("#situacaoVizualizar").html("");
    $("#valorTotalVizualizar").html("");
    $("#descontoVizualizar").html("");
    $("#valorComDescontoVizualizar").html("");
    $("#valorPagoVizualizar").html("");
}

function registrarPagamento(idPagamento){
    // Cria objeto Intl que será responsável por converter os valores para o formato da moeda brasileira
    let formatter = new Intl.NumberFormat([],{
        style: 'currency',
        currency: 'BRL'
    });

    // limpa modal
    limpaModalRegistroPagamento();

    $.ajax({
        type:"GET",
        url: "/contas/visualizar/"+idPagamento,
        context:this,
        success: function(pagamento){
            pagamento = JSON.parse(pagamento);
            // seta id Pagamento
            $("#formIdPagamento").val(pagamento.id);

            // Seta id do Pedido
            $("#formIdPedido").val(pagamento.pedido.id);

            // Nome do Cliente
            $("#nomeCliente").html(pagamento.pedido.cliente.user.name);


            // Data de vencimento
            let arrayDataVencimento = pagamento.dataVencimento.split('-');
            let dataVencimento = arrayDataVencimento[2] + "/" + arrayDataVencimento[1] + "/" +arrayDataVencimento[0];
            $("#dataVencimento").html(dataVencimento);


            // Tipo de Pedido
            if(pagamento.pedido.tipo == 'p')
                $("#tipo").html("Pedido");
            else if(pagamento.pedido.tipo == 'v')
                $("#tipo").html("Venda");
            else
            $("#tipo").html("Venda Mobile");

            // Situação. Retorna true se o pagamento está vencido e false caso não esteja
            let estaVencido = new Date(pagamento.dataVencimento).getTime() < new Date().getTime();

            // vencido
            if(pagamento.valorPago == null || pagamento.valorPago == 0){
                if(estaVencido == true)
                    $("#situacao").html("Pagamento Vencido");
                // aguardando pagamento
                else if(estaVencido == false)
                    $("#situacao").html("Aguardando Pagamento");

            }else{
            // pago
                $("#situacao").html("Pago");

            }


            // Valor Total
            let valorTotal = pagamento.valorTotalPagamento;
            $("#valorTotal").html(formatter.format(valorTotal));

            // Desconto
            let desconto = valorTotal * (pagamento.descontoPagamento / 100);
            $("#desconto").html(formatter.format(desconto));

            // Valor Com Desconto
            let valorComDesconto = valorTotal - desconto;

            $("#valorComDesconto").html(formatter.format(valorComDesconto));

            // Seta valor pago no form
            $("#formValorPago").val(valorComDesconto);

            // Valor total do pagamento que será usado na validação do form
            $("#valorTotalPagamento").val(valorComDesconto);
        }
    });
    $("#modalRegistrarPagamento").modal('show');
}

</script>

@endsection
