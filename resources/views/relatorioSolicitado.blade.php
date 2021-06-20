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
            <p style="line-height: 0.0">Data Pedido: {{date('d/m/Y', strtotime($pedido->created_at))}}</p>
            <p style="line-height: 1.6">Data de Entrega: {{date('d/m/Y', strtotime($pedido->dataEntrega))}}</p>
            <p style="line-height: 0.0">Responsável: {{$pedido->funcionario->user->name}}</p>

        @endforeach
    </div>
    <div class="col-sm-12">
        <table id="tabelaPedidos" class="table table-borderless table-striped" style="width: 100vw">
            <thead class="thead-primary">
            <tr style="height:20px">
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Quantidade Final</th>
            </tr>
            </thead>
            <tbody>
            @foreach($itens as $item)
                <tr align="center">
                    <td>{{$item->nomeProduto}}</td>
                    <td>{{$item->pesoSolicitado."KG"}}</td>
                    <td>{{$item->pesoFinal."KG"}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="row" style="margin-top: 20px">
            <div class="col-sm-6">
                <table  id="tabelaClientes" class="table table-borderless table-striped" style="width: 150px">
                    <thead class="thead-primary">
                    <tr style="height:20px">
                        <th>Total de Produtos</th>
                    </tr>
                    </thead>
                    <tr align="center">
                        <td>{{$count}}</td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>

@stop
