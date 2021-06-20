@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-sm-12">
            <div class="titulo-pagina">
                <div class="row">
                    <div class="col-sm-10">
                        <div class="titulo-pagina-nome">
                            <h2>Finalizar Pagamento</h2>
                        </div>
                    </div>
                </div>
            </div><!-- end titulo-pagina -->
        </div><!-- end col-->
    </div><!-- end row-->

    @if(isset($sucess))
        @if($sucess == false)
            <h4>Erro: O valor Pago é maior do que o valor do pedido!</h4>
        @endif
    @endif

    {{-- INFORMAÇÕES DO PEDIDO --}}
    <div class="row informacoes">
        <div class="col-sm-12">
            <h3>Informações da Venda</h3>
        </div>
    </div>

    <div class="row justify-content-center">
        {{-- Nome do Cliente --}}
        <div class="col-sm-6">
            <div class="card cardFinalizarPedidos">
                <div class="card-body">
                  <h5 class="card-title">Cliente</h5>
                  <p class="card-text"><h3>
                    @if(isset($pedido->cliente->user))
                        {{$pedido->cliente->user->name}}
                    @else
                       <?php $cliente = \App\Cliente::withTrashed()->find($pedido->cliente_id);
                             $cliente->user_id;
                             $user = \App\User::withTrashed()->find($cliente->user_id);
                        ?>
                        {{$user->name}}
                    @endif
                </h3></p>
                </div>
              </div>
        </div>
        
        {{-- Funcionário responsável pelo pagamento --}}
        <div class="col-sm-6">
            <div class="card cardFinalizarPedidos">
                <div class="card-body">
                  <h5 class="card-title">Funcionário Responsável pelo Pagamento</h5>
                  <p class="card-text"><h3>{{Auth::user()->name}}</h3></p>
                </div>
              </div>
        </div>
    </div> 

    <div class="row justify-content-center">
        {{-- Data do pedido --}}
        <div class="col-sm-6">
            <div class="card cardFinalizarPedidos">
                <div class="card-body">
                  <h5 class="card-title">Data da Venda</h5>
                  <p class="card-text"><h3>{{$pedido->created_at->format('d/m/Y')}}</h3></p>
                </div>
              </div>
        </div>

        {{--  Data de entrega do pedido--}}
        <div class="col-sm-6">
            <div class="card cardFinalizarPedidos">
                <div class="card-body">
                  <h5 class="card-title">Data de Entrega</h5>
                  <p class="card-text"><h3>{{date('d/m/Y', strtotime($pedido->dataEntrega))}}</h3></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        {{-- Valor Total do PEDIDO --}}
        <div class="col-sm-6">
            <div class="card cardFinalizarPedidos">
                <div class="card-body">
                    @if($pedido->status->status == "PAGO PARCIALMENTE" && isset($pagamento->valorTotalPagamento))
                        <h5 class="card-title">Valor Total da Venda</h5>
                        <p class="card-text"><h3 id="valorDoPedido">R$ {{money_format("%i",$pedido->valorTotal)}}</h3></p>
                    @else
                        <h5 class="card-title">Valor Total da Venda</h5>
                        <p class="card-text"><h3 id="valorDoPedido">R$ {{money_format("%i",$pedido->valorTotal)}}</h3></p>
                    @endif
                </div>
            </div>
        </div>
        {{-- Valor Total do Pagamento --}}
        <div class="col-sm-6">
            <div class="card cardFinalizarPedidos">
                <div class="card-body">
                    <h5 class="card-title">Valor Total do Pagamento</h5>
                    <p class="card-text"><h3 id="valorDoPedido">R$ {{money_format("%i",$valorTotalDoPagamento)}}</h3></p>
                </div>
            </div>
        </div>
    </div>
    {{-- FORMULÁRIO --}}
    <form id="formPagamento" action="{{route('venda.pagamento')}}" method="POST">    
        @csrf

        <div class="row justify-content-center">
            
            {{-- Valor do desconto --}}
            <div class="col-sm-6">
                <div class="card cardFinalizarPedidos">
                    <div class="card-body">
                        <h5 class="card-title">Valor do Desconto nos Itens</h5>
                        <p class="card-text"><h3 id="valorDesconto">R$ {{money_format("%i",$valorDoDesconto)}}</h3></p>                    
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6">
                <div class="card cardFinalizarPedidos">
                    <div class="card-body">
                        {{-- <h5 class="card-title">Valor Pago</h5>
                        <p class="card-text"><h3 style="float:left">R$</h3><h3 id="valorTtalPago">0</h3></p> --}}
                        
                        {{-- Entregador --}}
                        <h5 class="card-title">Selecionar Entregador</h5>
                        <p class="card-text" style="margin-bottom: 10px">
                            <select name="entregador_id" class="form-control" id="entregador" required>
                                <option value="" selected disabled>-- Selecionar Entregador --</option>
                                @foreach ($entregadores as $entregador)
                                <option value="{{$entregador->id}}">{{$entregador->user->name}}</option>
                                @endforeach
                            </select>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        {{-- INFORMAÇÕES DO PAGAMENTO --}}
        

        

        {{-- Inputs contendo os descontos adicionados na tela de finalizar venda --}}
        
        {{-- ID do pedido --}}
        <input type="hidden" name="pedido_id" value="{{$pedido->id}}">
        
        

        {{-- As Formas de pagamento dinâmicas são adicionadas aqui --}}
        <div id='divNovaFormaPagamento'></div>

        {{-- Botão Adicionar Forma de Pagamento --}}
        <div class="row justify-content-center">
            <div class="col-sm-4">
                <button id="bntNovaFormaPagamento" class="btn btn-primary-ludke" style="margin:20px 0 20px 0">
                    <h3>Adicionar Forma de Pagamento</h3>
                </button>
            </div>
        </div>

        <div class="row justify-content-center" style="margin:30px 0 30px 0;">
            <div class="col-sm-6" style="heigth:100px">
                <a href="{{route('listarVendas')}}" class="btn btn-secondary-ludke btn-pedido" >Voltar</a>
            </div>
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary-ludke btn-pedido">Finalizar Pagamento</button>
            </div>
        </div>
    </form>
</div>

@endsection

@section('javascript')

<script src="{{ asset('js/helper-pagamento.js') }}"></script>

<script type="text/javascript">
    // Data de hj
    const today = "<?php date_default_timezone_set('America/Sao_Paulo'); echo date('Y-m-d');?>";
    // Valor Total do Pagamento
    const valorTotal = <?= $valorTotalDoPagamento ?>;
    //Formas de pagamento
    const formasPagamento = <?= json_encode($formasPagamento) ?>; 
</script>    
@endsection