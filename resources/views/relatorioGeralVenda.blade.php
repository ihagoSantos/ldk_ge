@section('date',$date)
@section('content')
@section('titulo','Relatório de Vendas')
@extends('layouts.relatorios')
<style>
    thead{
        background-color: #ccc;
        color: black;
    }
</style>
<div class="row justify-content-center">
    <div class="col-sm-12">
        <table id="tabelaClientes" class="table table-borderless table-striped" style="width: 100vw">
            <thead class="thead-primary">
            <tr style="height:20px">
                <th>Código</th>
                <th>Nome Cliente</th>
                <th>Vendedor</th>
                <th>Data de Entrega</th>
                <th>Pedido</th>
                <th>Status</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>
            @foreach($vendas as $venda)
                <tr align="center">
                    <td>{{$venda->id}}</td>
                    <td >   @if(isset($venda->cliente->user))
                            {{$venda->cliente->user->name}}
                        @else
                            <?php $cliente = \App\Cliente::withTrashed()->find($venda->cliente_id);
                            $cliente->user_id;
                            $user = \App\User::withTrashed()->find($cliente->user_id);
                            ?>
                            {{$user->name}}
                        @endif</td>
                    <td>{{$venda->funcionario->user->name}}</td>
                    <td>{{date('d/m/Y',strtotime($venda->dataEntrega))}}</td>
                    <td>
                        <ul>
                            <li>
                                @foreach ($venda->itensPedidos as $itens)
                                    {{$itens->nomeProduto}} | {{$itens->pesoSolicitado}} KG,
                                @endforeach
                            </li>
                        </ul>
                    </td>
                    <td>{{$venda->status->status}}</td>
                    <td>{{$venda->valorTotal = number_format($venda->valorTotal, '2',',','.').' R$'}}</td>

                </tr>
            @endforeach
            </tbody>
        </table>
        <table  id="tabelaClientes" class="table table-borderless table-striped" style="width: 100vw; margin-top: 20px">
            <thead class="thead-primary">
            <tr style="height:20px">
                <th>Número total de Vendas</th>
                <th>Valor total das Vendas</th>
            </tr>
            </thead>
            <tbody>
                <tr align="center">
                    <td>{{$count}}</td>
                    <td>{{$total = number_format($total, '2',',','.').' R$'}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@stop
