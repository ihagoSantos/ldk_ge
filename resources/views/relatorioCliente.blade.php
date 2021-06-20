@section('date',$date)
@section('content')
@section('titulo','Relatório de Clientes')
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
                <th>Nome Reduzido</th>
                <th>Nome Responsável</th>
                <th>Cidade</th>
                <th>Func. Resp. </th>
            </tr>
            </thead>
            <tbody>
            @foreach($clientes as $cliente)
                <tr align="center">
                    <td>{{$cliente->id}}</td>
                    <td>{{$cliente->user->name}}</td>
                    <td>{{$cliente->nomeReduzido}}</td>
                    <td>{{$cliente->nomeResponsavel}}</td>
                    <td>{{$cliente->user->endereco->cidade}}</td>
                    @if($cliente->funcionario_id == '')
                        <td>{{$cliente->funcionario_id = ''}}</td>
                    @else
                        <td>{{$cliente->funcionario->user->name}}</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="row" style="margin-top: 20px">
            <div class="col-sm-6">
                <table  id="tabelaClientes" class="table table-borderless table-striped" style="width: 500px">
                    <thead class="thead-primary">
                    <tr style="height:20px">
                        <th>Total de Clientes</th>
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
