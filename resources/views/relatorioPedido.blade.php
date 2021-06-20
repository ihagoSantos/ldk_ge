@section('date',$date)
@section('content')
@section('titulo','Relatório de Pedido')
@extends('layouts.relatorios')
<style>
    thead{
        background-color: #ccc;
        color: black;
    }
</style>
<div class="row justify-content-center">
    <div>
        @foreach($clientes as $cliente)
            <p style="line-height: 0.1">Cliente: {{$cliente->nomeReduzido}}</p>
            <p style="line-height: 0.3">CPF/CNPJ: {{$cliente->cpfCnpj}}</p>
            <p style="line-height: -0.1">Endereço: {{$cliente->user->endereco->cidade . '-'.
                                                    $cliente->user->endereco->rua . '-Num:'.
                                                    $cliente->user->endereco->numero
                                                                                    }}</p>
            <p style="line-height: 1.4">Data Entrega: {{date('d/m/Y', strtotime($pedido->dataEntrega))}}</p>

        @endforeach
    </div>
    <div class="col-sm-12">
        <table id="tabelaPedidos" class="table table-borderless table-striped" style="width: 100vw">
            <thead class="thead-primary">
            <tr style="height:20px">
                <th>Produto</th>
                <th>Peso</th>
                <th>Preço KG</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>
            @foreach($itens as $item)
                <tr align="center">
                    <td>{{$item->nomeProduto}}</td>
                    <td>{{$item->pesoFinal. ' KG'}}</td>
                    <td>{{$item->produto->preco  = number_format($item->produto->preco, '2',',','.').' R$'}}</td>
                    <td>{{$item->valorReal = number_format($item->valorReal, '2',',','.').' R$'}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="row" style="margin-top: 20px">
            <div class="col-sm-6">
                <table  id="tabelaClientes" class="table table-borderless table-striped" style="width: 100vw; margin-top: 20px">
                    <thead class="thead-primary">
                    <tr style="height:20px">
                        <th>Número total de Produtos</th>
                        <th>Valor total do Pedido</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr align="center">
                        <td>{{$count}}</td>
                        <td>{{$soma = number_format($soma, '2',',','.').' R$'}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@stop
