@section('date',$date)
@section('content')
@section('titulo','Relatório de Produtos')
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
                <th>Nome</th>
                <th>Preco</th>
                <th>Categoria</th>
            </tr>
            </thead>
            <tbody>
            @foreach($produtos as $produto)
                <tr align="center">
                    <td>{{$produto->id}}</td>
                    <td >{{$produto->nome}}</td>
                    <td>{{$produto->preco = number_format($produto->preco, '2',',','.').' R$'}}</td>
                    <td>{{$produto->categoria->nome}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="row" style="margin-top: 20px">
            <div class="col-sm-6">
                <table  id="tabelaClientes" class="table table-borderless table-striped" style="width: 500px">
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
