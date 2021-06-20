<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContasPagar;
use App\CentroCusto;
use App\FontePagamento;
use App\Fornecedor;

class ContasPagarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fonte_pagamento
        // centro_custo
        
        // $contas = ContasPagar::with(['centroCusto','fontePagamento','fornecedor'])->get();
        // dd($contas);
        
        $contas = ContasPagar::with(['centroCusto','fontePagamento','fornecedor'])->paginate(25);
        $fornecedores = Fornecedor::all();
        $centroCusto = CentroCusto::all();
        $fontePagamento = FontePagamento::all();
        // dd($contas[1]->centroCusto()->get());
        
        return view('contasPagar',['contas'=>$contas, 
            'fornecedores'=>$fornecedores,
            'centroCusto'=>$centroCusto,
            'fontePagamento'=>$fontePagamento,
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dados = $request->all();
        // dd($dados);

        // $fontePagamento = new FontePagamento();
        // $fontePagamento->nome = $dados['nomeFontePagamento'];
        // $fontePagamento->agencia = $dados['agenciaFontePagamento'];
        // $fontePagamento->conta = $dados['contaFontePagamento'];
        // $fontePagamento->obs = $dados['obsFontePagamento'];
        // $fontePagamento->save();

        
        for($i = 0; $i < sizeof($dados['valorTotal']); $i++){
          $conta = new ContasPagar();

          $conta->descricao = $dados['desc'][$i];
          $conta->dataPagamento = $dados['dataPagamento'][$i];
          $conta->dataVencimento = $dados['dataVencimento'][$i];
          $conta->obs = $dados['obsConta'][$i];
          $conta->status = 0;
          $conta->valorTotalPgm = round($dados['valorTotal'][$i], 2);
          $conta->centroCusto_id = $dados['centroCusto'];
          $conta->fontePagamento_id = $dados['fontePagamento'];
          $conta->fornecedor_id = $dados['fornecedor'];
  
          $conta->save();
          
          
        }
        return json_encode(['status'=> 1, 'msg'=>'Conta Cadastrada com Sucesso!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $conta = ContasPagar::with(['centroCusto','fontePagamento','fornecedor'])->find($id);
        $conta->fornecedor->telefone;
        
        return json_encode($conta);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $conta = ContasPagar::find($id);
        if(isset($conta)){
            $conta->descricao = $request['desc'];
            $conta->dataPagamento = $request['dataPagamento'];
            $conta->dataVencimento = $request['dataVencimento'];
            $conta->obs = $request['obsConta'];
            $conta->valorTotalPgm = round($request['valorTotal'],2);
            $conta->centroCusto_id = $request['centroCusto'];
            $conta->fontePagamento_id = $request['fontePagamento'];
            $conta->fornecedor_id = $request['fornecedor'];

            $conta->save();

            return json_encode(['status'=> true, 'msg'=>'Conta Atualizada com Sucesso!']);
        }
        else{
            return json_encode(['status'=> false, 'msg'=>'Não foi possível encontrar esta conta']);
        }

        
    }

    public function registrarPagamento(Request $request){
        
        $conta = ContasPagar::find($request['id']);
        if(isset($conta)){
            $conta->status = 1;
            $conta->valorPago = $conta->valorTotalPgm;
            $conta->save();
            return json_encode(['status'=> true, 'msg'=>'Pagamento de Conta Registrada com Sucesso!']);
        }
        else{
            return json_encode(['status'=> false, 'msg'=>'Não foi possível encontrar esta conta']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //dd($id);
        $conta = ContasPagar::find($id);
        if(isset($conta)){
            $conta->delete();
            return json_encode(['status'=> true, 'msg'=>'Conta Removida com Sucesso!']);
        }
        else{
            return json_encode(['status'=> false, 'msg'=>'Não foi possível encontrar esta conta']);
        }
    }
}

