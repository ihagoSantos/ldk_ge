<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Pagamento;
use App\Pedido;
use App\FormaPagamento;
use Illuminate\Support\Facades\Auth;

class ContasReceber extends Controller
{
    public function index($idPedido = null){
        if(isset($idPedido)){
            $pagamentosAbertos = Pagamento::where('pedido_id',$idPedido)->where('status','aberto')->orderBy('dataVencimento')->paginate(25);
            $pagamentosFechados = Pagamento::where('pedido_id',$idPedido)->where('status','fechado')->orderBy('dataVencimento')->paginate(25);

            $listarTodos = true;
        }else{
            $pagamentosAbertos = Pagamento::where('status','aberto')->orderBy('dataVencimento')->paginate(25);
            $pagamentosFechados = Pagamento::where('status','fechado')->orderBy('dataVencimento')->paginate(25);
            $listarTodos = false;
        }
        $infoMensal = self::infoMensal();
        
        return view('contasReceber',['pagamentosAbertos'=>$pagamentosAbertos,'pagamentosFechados'=>$pagamentosFechados,'infoMensal' => $infoMensal,'listarTodos' => $listarTodos]);
    }

    public function infoMensal(){
        
        
        $dataHoje = strtotime(date('Y-m-d'));
        $inicioMes = date('Y-m-d',mktime(0,0,0, date('m'), 1, date('Y'))); // inicio do mês
        $finalMes = date("Y-m-t"); // final do Mês 

        // retorna todos os pagamentos
        //retorna 
        $pagamentosMensal = Pagamento::whereDate('dataVencimento','>=',$inicioMes)
            ->whereDate('dataVencimento','<=',$finalMes)->get();


        $totalPagamentosAberto = 0;
        $totalPagamentosFechados = 0;
        $totalPagamentosVencidos = 0;
        $totalPagamentosAguardando = 0;
        
        
        $totalValorRecebido = 0;
        $totalValorAguardandoPagamento = 0;

        $contValorPago = 0;
        $contValorAguardandoPagamento = 0;
        foreach ($pagamentosMensal as $pagamento) {

            $contValorPago += $pagamento->valorPago;
            $contValorAguardandoPagamento += $pagamento->valorTotalPagamento - ($pagamento->descontoPagamento / 100);

            // conta Pagamentos abertos e fechados
            if($pagamento->status == 'aberto'){
                $totalPagamentosAberto+=1;
            }
            else{
                $totalPagamentosFechados+=1;
            }

            // //Conta pagamentos vencidos e aguardando

            // if(date('Y-m-d',strtotime($pagamento->dataVencimento)) < $dataHoje && $pagamento->valorPago == 0){
            //     $totalPagamentosVencidos += 1;
            // }
            // if(date('Y-m-d',strtotime($pagamento->dataVencimento)) >= $dataHoje && $pagamento->valorPago == 0){
                
            //     $totalPagamentosAguardando += 1;
            // }

        }

        // configura array infoGeral
        $infoGeral['totalPagamentos'] = count($pagamentosMensal);
        $infoGeral['valorTotalPago'] = $contValorPago ;
        $infoGeral['valorTotalAguardando'] = $contValorAguardandoPagamento - $contValorPago;
        $infoGeral['totalPagamentosPagos'] = $totalPagamentosFechados;
        $infoGeral['totalPagamentosVencidos'] = $totalPagamentosVencidos ;
        $infoGeral['totalPagamentosAguardando'] = $totalPagamentosAguardando ;


        // dd($pagamentosMensal, $infoGeral,$inicioMes,$finalMes, $dataHoje);
        return $infoGeral;
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
        //
        // dd($request->all());
        if($request['dataVencimentoNovoPagamento']){

            // Cria novo pagamento
            $newPagamento = new Pagamento();
            $newPagamento->dataVencimento = $request['dataVencimentoNovoPagamento'];
            $newPagamento->descontoPagamento = 0;
            $newPagamento->valorTotalPagamento = $request['valorTotalPagamento'] - $request['formValorPago']; //
            $newPagamento->valorPago = 0;
            $newPagamento->status = "aberto";
            $newPagamento->funcionario_id = auth()->user()->funcionario->id;
            $newPagamento->pedido_id = $request['formIdPedido'];
            $newPagamento->formaPagamento_id = 1;
            $newPagamento->save();

            // Salva o pagamento existente

            $pagamento = Pagamento::find($request['formIdPagamento']);
            $pagamento->valorTotalPagamento = $request['formValorPago'];//o valor total agora será o valor pago
            $pagamento->valorPago = $request['formValorPago'];
            $pagamento->dataPagamento = date('Y-m-d');
            $pagamento->status = "fechado";
            $pagamento->save();
        }else{
            $pagamento = Pagamento::find($request['formIdPagamento']);
            $pagamento->valorPago = $request['formValorPago'];
            $pagamento->dataPagamento = date('Y-m-d');
            $pagamento->status = "fechado";
            $pagamento->save();
        }
        // dd($request->all(),$pagamento);
        return redirect()->route('contas.receber');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pagamento = Pagamento::find($id);
        
        $pagamento->pedido->cliente->user;//carrega as relações do pagamento        

        return json_encode($pagamento);
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
    public function editarPagamentoContasReceber($id)
    {
        
        $pagamento = Pagamento::find($id);
        $formasPagamento = FormaPagamento::all();
        
        return view('editarPagamentoContas',['pagamento'=>$pagamento,'formasPagamento'=>$formasPagamento]);

    }
    public function updatePagamentoContasReceber(Request $request, $id){
        // Atualiza o pagamento antigo
        // dd($request->all());
        $pagamento = Pagamento::find($id);
        $pagamento->formaPagamento_id = $request['updateFormaPagamento'];
        $pagamento->valorTotalPagamento = $request['updateValorTotalPagamento'];
        $pagamento->descontoPagamento = $request['updateDescontoPagamento'];
        $pagamento->dataVencimento = $request['updateDataVencimento'];
        $pagamento->obs = $request['updateObs'];
        $pagamento->save();
        
        
        // Salva as novas formas de pagamento
        if(isset($request['valorTotalPagamento'])){
            for($i = 0; $i < sizeof($request['valorTotalPagamento']); $i++){
                $newPagamento = new Pagamento();
                $newPagamento->obs = $request['obs'][$i];
                $newPagamento->descontoPagamento = $request['descontoPagamento'][$i];
                $newPagamento->dataVencimento = $request['dataVencimento'][$i];
                $newPagamento->valorTotalPagamento = $request['valorTotalPagamento'][$i];
                $newPagamento->status = 'aberto';
                $newPagamento->funcionario_id = auth()->user()->funcionario->id;
                $newPagamento->pedido_id = $request['idPedido'];
                $newPagamento->formaPagamento_id = $request['formaPagamento'][$i];
                
                $newPagamento->save();
            }
        }

        return redirect()->route('contas.receber');

    }

    /**
     * Redireciona para tela de editar os pagamentos do pedido e venda
     */
    public function editarPagamentoPedidoVenda($id){
        $pedido = Pedido::with(['pagamento'])->where('id',$id)->first();
        $formasPagamento = FormaPagamento::all();
        // dd($pedido->id);
        return view('editarPagamentoPedidoVenda',['pedido'=>$pedido,'formasPagamento'=>$formasPagamento]);
    }

    /**
     * Edita as formas de pagamento dos pedidos e vendas
     */
    public function updatePagamentoPedidoVenda(Request $request, $id){
        // Busca o pedido
        $pedido = Pedido::find($id);
        
        // Atualiza os pagamentos referente ao pedido
        if(isset($request['idPagamento'])){
            // Percorre os arrays passados no input
            for($i = 0; $i < sizeof($request['idPagamento']); $i++){
                // busca o pagamento pelo id
                $updatePagamento = Pagamento::find($request['idPagamento'][$i]);
                // se achar o pagamento, atualiza os valores e salva
                if(isset($updatePagamento)){
                    $updatePagamento->obs = $request['updateObs'][$i];
                    $updatePagamento->descontoPagamento = $request['updateDescontoPagamento'][$i];
                    $updatePagamento->valorTotalPagamento = $request['updateValorTotalPagamento'][$i];
                    $updatePagamento->status = 'aberto';
                    $updatePagamento->funcionario_id = auth()->user()->funcionario->id;
                    $updatePagamento->pedido_id = $id;
                    $updatePagamento->formaPagamento_id = $request['updateFormaPagamento'][$i];
                    $updatePagamento->save();
                }
            }
        }

        // Salva as novas formas de pagamento
        if(isset($request['valorTotalPagamento'])){
            for($i = 0; $i < sizeof($request['valorTotalPagamento']); $i++){
                $newPagamento = new Pagamento();
                $newPagamento->obs = $request['obs'][$i];
                $newPagamento->descontoPagamento = $request['descontoPagamento'][$i];
                $newPagamento->dataVencimento = $request['dataVencimento'][$i];
                $newPagamento->valorTotalPagamento = $request['valorTotalPagamento'][$i];
                $newPagamento->status = 'aberto';
                $newPagamento->funcionario_id = auth()->user()->funcionario->id;
                $newPagamento->pedido_id = $id;
                $newPagamento->formaPagamento_id = $request['formaPagamento'][$i];
                
                $newPagamento->save();
            }
        }
        
        // redireciona de acordo com o tipo do pedido 11
        if($pedido->tipo == "p"){
            return redirect()->route('listarPedidos');
        }else{
            return redirect()->route('listarVendas');
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
        //
    }
}
