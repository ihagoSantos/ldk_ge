@extends('layouts.app')

@section('content')


<div class="container-fluid">
    <div class="row justify-content-center">

        <div class="col-sm-12">
            <div class="titulo-pagina">
                <div class="row d-flex justify-content-between">
                    <div class="col-sm-7">
                        <div class="titulo-pagina-nome">
                            <h2>Pedidos</h2>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        {{-- <a href="#" data-toggle="modal" data-target="#dlgFiltro"class="btn btn-primary-ludke">Filtrar</a> --}}
                        <button id="btnFiltrar" type="button" class="btn btn-primary-ludke" data-toggle="modal" data-target="#modalFiltro">
                            Filtrar
                          </button>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{route('pedidos')}}" class="btn btn-primary-ludke">Novo Pedido</a>
                    </div>

                </div>
            </div><!-- end titulo-pagina -->
        </div><!-- end col-->
    </div><!-- end row-->

    @if(isset($achou) && $achou == true)
        <div class="row" style="margin-bottom:10px">
            <div class="col-sm-12">
                <span class="badge badge-light" style="padding:5px"><h4>Filtro: {{$tipoFiltro}}</h4></span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 limparBusca">
                <a href="{{route('listarPedidos')}}">
                    <button class="btn btn-outline-danger">Listar Todos</button>
                </a>

            </div>
        </div>

    @endif

    {{-- Verifica se está listando o pedido visualizado em Contas a Receber --}}
    @if (isset($listarPedidoConta) && $listarPedidoConta == true)
        <div class="row">
            <div class="col-sm-12 limparBusca">
                <a href="{{route('listarPedidos')}}">
                    <button class="btn btn-outline-danger">Listar Todos</button>
                </a>

            </div>
        </div>
    @endif

    {{-- Nav-tabs --}}
    <ul class="nav nav-tabs" id="myTabContas" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link-contas active" id="solicitado_pesado-tab" data-toggle="tab" href="#solicitado_pesado" role="tab" aria-controls="solicitado_pesado" aria-selected="true"><h5>Solicitado/Pesado</h5></a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link-contas" id="entregue-tab" data-toggle="tab" href="#entregue" role="tab" aria-controls="entregue" aria-selected="false"><h5>Entregue</h5></a>
        </li>
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content" id="myTabContent">
        {{-- Pedidos SOLICITADOS/PESADOS --}}
        <div class="tab-pane fade show active" id="solicitado_pesado" role="tabpanel" aria-labelledby="solicitado_pesado-tab">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <table id="tabelaPedidos" class="table table-hover table-responsive-sm">
                        <thead class="thead-primary">
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Funcionário</th>
                            <th>Data da Entrega</th>
                            <th>Pedido</th>
                            <th>Status</th>
                            <th>Valor Total</th>
        
        
                            <th>Ações</th>
        
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedidos as $pedido)
                            <tr id="{{$pedido->id}}">
                                <td>{{$pedido->id}}</td>
                                <td>
                                    @if(isset($pedido->cliente->user))
                                        {{$pedido->cliente->user->name}}
                                    @else
                                       <?php $cliente = \App\Cliente::withTrashed()->find($pedido->cliente_id);
                                             $cliente->user_id;
                                             $user = \App\User::withTrashed()->find($cliente->user_id);
                                        ?>
                                        {{$user->name}}
                                    @endif
                                </td>
                                <td>{{$pedido->funcionario->user->name}}</td>
                                <td>{{date('d/m/Y',strtotime($pedido->dataEntrega))}}</td>
                                <td>
                                    <ul>
                                        @foreach ($pedido->itensPedidos as $itens)
                                        <li>{{$itens->nomeProduto}} | {{$itens->pesoSolicitado}} KG</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{$pedido->status->status}}</td>
                                <td>
                                    {{-- R$ {{$pedido->valorTotal}} --}}
                                    R$ {{money_format('%i',$pedido->valorTotal)}}
                                </td>
                                    {{-- Pedido com status SOLICITADO --}}
                                    @if($pedido->status->status == "SOLICITADO")
                                        <td>
                                            {{-- PESAR Pedido --}}
                                            <a href="{{route('pedido.pesarPedido',['id'=>$pedido->id])}}" title="Pesar Pedido">
                                                <img id="pesar" class="icone" src="{{asset('img/balanca.svg')}}" >
                                            </a>
                                           {{-- Editar Pedido --}}
                                            <a href="/pedidos/edit/{{$pedido->id}}" title="Editar Pedido">
                                                <img id="editar" class="icone" src="{{asset('img/edit-solid.svg')}}" >
                                            </a>
                                            {{-- Imprimir pedido solicitado --}}
                                            <a href={{route('pedido.solicitado',['id'=>$pedido->id])}}  target="_blank" title="Pedido Solicitado">
                                                <img id="" class="icone" src="{{asset('img/print.svg')}}" >
                                            </a>
                                            {{-- Excluir Pedido --}}
                                            <a href="#" onclick="excluirPedido({{$pedido->id}})" title="Excluir Pedido">
                                                <img id="deletar" class="icone" src="{{asset('img/trash-alt-solid.svg')}}" width="20" height="23">
                                            </a>
                                        </td>
                                    {{-- Pedido com status PESADO --}}
                                    @elseif($pedido->status->status == "PESADO")
                                        <td>
                                            {{-- Editar Pedido --}}
                                            <a href="/pedidos/edit/{{$pedido->id}}" title="Editar Pedido">
                                                <img id="editar" class="icone" src="{{asset('img/edit-solid.svg')}}" >
                                            </a>
                                            {{-- Finalizar Pagamento --}}
                                            <a href="{{route('pedido.concluirPedido',['id'=>$pedido->id])}}" title="Finalizar Pagamento">
                                                <img id="pagar" class="icone" src="{{asset('img/cash-register-solid-black.svg')}}" >
                                            </a>
                                            {{-- Registrar Entrega do pedido --}}
                                            <a href="{{route('pedido.indexRegistrarEntrega',['id'=>$pedido->id])}}" title="Registrar Entrega">
                                                <img id="" class="icone" src="{{asset('img/truck-solid.svg')}}" >
                                            </a>
                                            {{-- Imprimir pedido --}}
                                            <a href={{route('pedido.relatorio',['id'=>$pedido->id])}} target="_blank" title="Imprimir Pedido">
                                                <img id="" class="icone" src="{{asset('img/print.svg')}}" >
                                            </a>
                                            {{-- Excluir Pedido --}}
                                            <a href="#" onclick="excluirPedido({{$pedido->id}})" title="Excluir Pedido">
                                                <img id="deletar" class="icone" src="{{asset('img/trash-alt-solid.svg')}}" width="20" height="23">
                                            </a>
                                        </td>
                                    @else
                                        <td>
                                            <a href="#" onclick="excluirPedido({{$pedido->id}})" title="Excluir Pedido">
                                                <img id="deletar" class="icone" src="{{asset('img/trash-alt-solid.svg')}}"  >
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table> <!-- end table -->
                </div><!-- end col-->
            </div><!-- end row-->
        
            <div class="row justify-content-center">
                @if ($pedidos != [])
                    @if (isset($filtro))
                    {{ $pedidos->appends($filtro)->links() }}
        
                    @else
                    {{ $pedidos->links() }}
                    @endif
                @endif
            </div>
        </div>

        {{-- ENTREGUES --}}
        <div class="tab-pane fade" id="entregue" role="tabpanel" aria-labelledby="entregue-tab">
            {{-- PEDIDOS ENTREGUES --}}
            <div class="tab-pane fade show active" id="solicitado_pesado" role="tabpanel" aria-labelledby="solicitado_pesado-tab">
                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <table id="tabelaPedidos" class="table table-hover table-responsive-sm">
                            <thead class="thead-primary">
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Funcionário</th>
                                <th>Data da Entrega</th>
                                <th>Pedido</th>
                                <th>Status</th>
                                <th>Valor Total</th>
            
            
                                <th>Ações</th>
            
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($pedidosEntregues as $pedido)
                                <tr id="{{$pedido->id}}">
                                    <td>{{$pedido->id}}</td>
                                    <td>
                                        @if(isset($pedido->cliente->user))
                                            {{$pedido->cliente->user->name}}
                                        @else
                                           <?php $cliente = \App\Cliente::withTrashed()->find($pedido->cliente_id);
                                                 $cliente->user_id;
                                                 $user = \App\User::withTrashed()->find($cliente->user_id);
                                            ?>
                                            {{$user->name}}
                                        @endif
                                    </td>
                                    <td>{{$pedido->funcionario->user->name}}</td>
                                    <td>{{date('d/m/Y',strtotime($pedido->dataEntrega))}}</td>
                                    <td>
                                        <ul>
                                            @foreach ($pedido->itensPedidos as $itens)
                                            <li>{{$itens->nomeProduto}} | {{$itens->pesoSolicitado}} KG</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{$pedido->status->status}}</td>
                                    <td>
                                        {{-- R$ {{$pedido->valorTotal}} --}}
                                        R$ {{money_format('%i',$pedido->valorTotal)}}
                                    </td>
                                        
                                    <td>
                                        {{-- Se o pedido não possui pagamento, exibe icone de editar e pagar --}}
                                        @if(count($pedido->pagamento) == 0)
                                            {{-- Editar Pedido --}}
                                            <a href="/pedidos/edit/{{$pedido->id}}" title="Editar Pedido">
                                                <img id="editar" class="icone" src="{{asset('img/edit-solid.svg')}}" >
                                            </a>
                                            {{-- Finalizar Pagamento --}}
                                            <a href="{{route('pedido.concluirPedido',['id'=>$pedido->id])}}" title="Finalizar Pagamento">
                                                <img id="pagar" class="icone" src="{{asset('img/cash-register-solid-black.svg')}}" >
                                            </a>
                                            
                                        {{-- Caso o pedido possua pagamento, exibe icone de editar pagamento e contas a receber --}}
                                        @else
                                            {{-- Editar Pagamento--}}
                                            <a href={{route('contas.editarPagamentoPedidoVenda',['id' => $pedido->id])}} title="Editar Pagamento">
                                                <img id="pagar" class="icone" src="{{asset('img/hand-holding-usd-solid.svg')}}" >
                                            </a>
                                            {{-- Contas a pagar --}}
                                            <a href={{route('contas.receber',['idPedido' => $pedido->id])}} title="Contas a receber">
                                                <img id="pagar" class="icone" src="{{asset('img/money-bill-wave-solid.svg')}}" >
                                            </a>
                                        @endif
                                        {{-- Imprimir pedido --}}
    
                                        <a href={{route('pedido.relatorio',['id'=>$pedido->id])}} target="_blank" title="Imprimir Pedido">
                                            <img id="" class="icone" src="{{asset('img/print.svg')}}" >
                                        </a>
                                        {{-- Excluir Pedido --}}
    
                                        <a href="#" onclick="excluirPedido({{$pedido->id}})" title="Excluir Pedido">
                                            <img id="deletar" class="icone" src="{{asset('img/trash-alt-solid.svg')}}"  width="20" height="23">
                                        </a>
                                    </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table> <!-- end table -->
                    </div><!-- end col-->
                </div><!-- end row-->
            
                <div class="row justify-content-center">
                    @if ($pedidosEntregues != [])
                        @if (isset($filtro))
                        {{ $pedidosEntregues->appends($filtro)->links() }}
            
                        @else
                        {{ $pedidosEntregues->links() }}
                        @endif
                    @endif
                </div>
        </div>
    </div>

    

</div>
<!-- Modal -->
<div class="modal fade" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Filtrar Pedido</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formFiltro" action="{{route('pedido.filtrar')}}" method="POST">
            @csrf
            <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="cliente">Nome do Cliente</label>
                            <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Nome do Cliente">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="cliente">Nome Reduzido</label>
                            <input type="text" class="form-control" id="cliente" name="nomeReduzido" placeholder="Nome Reduzido">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="dataEntregaInicial">Data de Entrega Inicial</label>
                            <input type="date" class="form-control" id="dataEntregaInicial" name="dataEntregaInicial">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="dataEntregaFinal">Data de Entrega Final</label>
                            <input type="date" class="form-control" id="dataEntregaFinal" name="dataEntregaFinal">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="status_id">Status</label>
                            <select class="form-control" name="status_id" id="status_id">
                                <option value="" disabled selected>-- STATUS --</option>
                                <option value="1">SOLICITADO</option>
                                <option value="2">PESADO</option>
                                {{-- <option value="3">ENTREGUE</option> --}}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnSubmitModal" class="btn btn-danger">Filtrar</button>
                </div>
            </form>
      </div>
    </div>
  </div>

@endsection

@section('javascript')

<script type="text/javascript">

    $(function(){

        // Configuração do ajax com token csrf
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // cliente
        // dataEntrega
        // status
        $('#btnFiltrar').click(function(){
            $('#cliente').val('');
            $('#dataEntrega').val('');
            $('#status').val('');
        });
    });

    function excluirPedido(id){
        confirma = confirm("Você deseja excluir o pedido?");
        if(confirma){
            $.ajax({
                type:"DELETE",
                url: "/pedidos/excluir/"+id,
                context:this,
                success: function(){

                    console.log("deletou");
                    linhas = $("#tabelaPedidos>tbody>tr");
                    e = linhas.filter(function(i,elemento){
                        return elemento.cells[0].textContent == id;//faz um filtro na linha e retorna a que tiver o id igual ao informado

                    });
                    if(e){
                        e.remove();
                    }
                }
            });
        }
    }

</script>

@endsection
