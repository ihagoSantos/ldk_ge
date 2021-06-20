@section('date',$date)
@section('content')
@section('titulo','Relatório dos Pedidos')
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
                <th>Func. Responsavel</th>
                <th>Data de Entrega</th>
                <th>Pedido</th>
                <th>Status</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>
            @foreach($pedidos as $pedido)
                <tr align="center">
                    <td>{{$pedido->id}}</td>
                    <td >   @if(isset($pedido->cliente->user))
                            {{$pedido->cliente->user->name}}
                        @else
                            <?php
                            $cliente = \App\Cliente::withTrashed()->find($pedido->cliente_id);

                            $user = \App\User::withTrashed()->find($cliente->user_id);
                            ?>
                            {{$user->name}}
                        @endif</td>
                    <td>{{$pedido->funcionario->user->name}}</td>
                    <td>{{date('d/m/Y',strtotime($pedido->dataEntrega))}}</td>
                    <td>
                        <ul>
                            <li>
                                @foreach ($pedido->itensPedidos as $itens)
                                {{$itens->nomeProduto}} | {{$itens->pesoSolicitado}} KG,
                                @endforeach
                            </li>
                        </ul>
                    </td>
                    <td>{{$pedido->status->status}}</td>
                    <td>{{$pedido->valorTotal = number_format($pedido->valorTotal, '2',',','.').' R$'}}</td>

                </tr>
            @endforeach
            </tbody>
        </table>
        <table  id="tabelaClientes" class="table table-borderless table-striped" style="width: 100vw; margin-top: 20px">
            <thead class="thead-primary">
            <tr style="height:20px">
                <th>Número total de pedidos</th>
                <th>Valor total dos Pedidos</th>
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
