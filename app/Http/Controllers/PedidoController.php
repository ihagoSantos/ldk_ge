<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cliente;
use App\Produto;
use App\User;
use App\ItensPedido;
use App\Pedido;
use App\Funcionario;
use App\Status;
use App\Pagamento;
use App\Cargo;
use App\FormaPagamento;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pedido');
    }

    public function indexListarPedidos(){
        // Busca os pedidos com status SOLICITADO e PESADO
        $pedidos = Pedido::with(['status','pagamento'])->
            where('tipo','p')->
            where(function($query){
                $query->where('status_id',1)->//SOLICITADO
                        orWhere('status_id',2);//PESADO
            })->
            //orderBy('status_id')->
            orderBy('dataEntrega', 'DESC')->
            paginate(25);

        // Busca Pedidos com status ENTREGUE
        $pedidosEntregues = Pedido::with(['status','pagamento'])->
            where('tipo','p')->
            where('status_id',3)->//ENTREGUE
            orderBy('dataEntrega','DESC')->
            paginate(25);
        #dd($pedidosEntregues);    
        // dd($pedidosEntregues[0]->pagamento);
        return view('listarPedido',['pedidos'=>$pedidos, 'pedidosEntregues'=>$pedidosEntregues]);
    }
    /**
     * Redireciona para tela de listar pedidos com o pedido
     * @param $id pedido
     */
    function show($id){
        $pedidos = Pedido::with(['status','pagamento'])->
                            where('id',$id)->
                            where(function($query){
                                $query->where('status_id',1)->//SOLICITADO
                                        orWhere('status_id',2);//PESADO
                            })->
                            orderBy('status_id')->
                            orderBy('dataEntrega', 'DESC')->paginate(25);

        $pedidosEntregues = Pedido::with(['status','pagamento'])->
                            where('id',$id)->
                            where('status_id',3)->
                            orderBy('dataEntrega','DESC')->paginate(25);
        return view('listarPedido',['pedidos'=>$pedidos,'pedidosEntregues'=>$pedidosEntregues,'listarPedidoConta'=>true]);
    }
    public function indexPagamento($id){
        // Pedido
        $pedido = Pedido::with(['cliente'])->find($id);

        // Itens do Pedido
        $itensPedido = ItensPedido::where('pedido_id',$pedido->id)->get();

        $valorTotalDoPagamento = 0;
        $valorDoDesconto = 0;

        foreach ($itensPedido as $item) {
            if(isset($item->valorComDesconto)){
                $valorTotalDoPagamento += floatval($item->valorComDesconto);
                $valorDoDesconto += floatval($item->valorReal) - floatval($item->valorComDesconto);
            }else{
                $valorTotalDoPagamento += floatval($item->valorReal);
            }
        }

        //------------DEBUG--------------------------
        // dd($pedido,$itensPedido, $valorTotalDoPagamento,$valorDoDesconto);
        $entregador_id = Cargo::where('nome','ENTREGADOR')->pluck('id')->first();
        $entregadores = Funcionario::all();
        $formasPagamento = FormaPagamento::all();
        $valorTotalDoPagamento = round($valorTotalDoPagamento, 2);
        //dd($valorTotalDoPagamento);


        //dd('AQUIIII');
        return view('pagamento',
            [
                'pedido'=>$pedido,
                'valorTotalDoPagamento'=>$valorTotalDoPagamento,
                'valorDoDesconto'=>$valorDoDesconto,
                'entregadores'=>$entregadores,
                'formasPagamento'=>$formasPagamento,
            ]);
    }
    /**
     * Função que redireciona para tela de registrar entrega do pedido
     * @param Integer $id
     * @return View registrarEntregaPedido
     */
    public function indexRegistrarEntregaPedido($id){
        // dd($id);
        // Pedido
        $pedido = Pedido::with(['cliente'])->find($id);

        // Itens do Pedido
        $itensPedido = ItensPedido::where('pedido_id',$pedido->id)->get();

        $valorTotalDoPagamento = 0;
        $valorDoDesconto = 0;

        foreach ($itensPedido as $item) {
            $valorTotalDoPagamento += floatval($item->valorComDesconto);
            $valorDoDesconto += floatval($item->valorReal) - floatval($item->valorComDesconto);
        }
        
        //------------DEBUG--------------------------
        // dd($pedido,$itensPedido, $valorTotalDoPagamento,$valorDoDesconto);
        $entregador_id = Cargo::where('nome','ENTREGADOR')->pluck('id')->first();
        //dd($entregador_id);
        //$entregadores = Funcionario::with(['user'])->where('id',$pedido->funcionario_id)->
          //                              orwhere('cargo_id',$entregador_id)->get();
        $entregadores = Funcionario::all();
        return view('registrarEntregaPedido',
        [
            'pedido'=>$pedido,
            'valorTotalDoPagamento'=>$valorTotalDoPagamento,
            'valorDoDesconto'=>$valorDoDesconto,
            'entregadores'=>$entregadores,
        ]);
    }
    /**
     * Função que registra a entrega do pedido informando o funcionario que entregou
     * @param Integer $id
     * @return View listarPedidos
     */
    public function registrarEntregaPedido(Request $request){
        // dd($request->all());
        $pedido = Pedido::find($request['pedido_id']);
        $pedido->entregador_id = $request['entregador_id'];
        $data = $request['dataEntrega'];
        $data = str_replace('/', '-', $data);
        $date = date('Y-m-d', strtotime($data));
        //dd($date);
        $pedido->dataEntrega = $date;
        //dd($pedido->dataEntrega);
        $status_id = Status::where('status','ENTREGUE')->pluck('id')->first();
        $pedido->status_id = $status_id;

        //dd($pedido);

        $pedido->save();
        return redirect()->route('listarPedidos');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pedido = Pedido::with(['itensPedidos'])->find($id);
        if(isset($pedido)){
            for($i = 0; $i< count($pedido->itensPedidos); $i++){
                $produto = Produto::find($pedido->itensPedidos[$i]->produto_id);
                $pedido->itensPedidos[$i]["precoProduto"] = $produto->preco;
            }
            $cliente = Cliente::with('user')->find($pedido->cliente_id);
            $funcionario = Funcionario::with('user')->find($pedido->funcionario_id);


            if(isset($cliente->user->name)){
               $pedido["nomeCliente"] = $cliente->user->name;
            }
            else{
                $cliente = \App\Cliente::withTrashed()->find($pedido->cliente_id);
                $user = \App\User::withTrashed()->find($cliente->user_id);
                $pedido["nomeCliente"] = $user->name;
            }
            // $pedido["valorProduto"]= $produto->preco;

            $pedido["nomeFuncionario"] = $funcionario->user->name;


            return view('editarPedido')->with(["pedido"=>$pedido]);
        }

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

        $pedido = Pedido::find($request->input('id'));
        $valorTotal = floatval($pedido->valorTotal);

        // Lista com novos pedidos Adicionados
        $listaProdutos = $request->input('listaProdutos');
        if(isset($listaProdutos)){
            foreach($request->input('listaProdutos') as $item){
                $itemPedido = new ItensPedido();
                $produto = Produto::find($item['produto_id']);
                if(isset($produto)){
                    $itemPedido->pesoSolicitado = floatval($item['peso']);
                    $itemPedido->pesoFinal = floatval($item['peso']);
                    $itemPedido->valorReal = floatval($produto->preco * $item['peso']);
                    $itemPedido->nomeProduto = $produto->nome;
                    $itemPedido->produto_id = $produto->id;
                    $itemPedido->pedido_id = $pedido->id;

                    $itemPedido->save();

                    $valorTotal += $produto->preco * floatval($item['peso']); //soma ao valor total
                }
            }

        }

        // forma de pagamento só é definida na conclusão do pedido

        $pedido->dataEntrega = $request->input('dataEntrega');
        // $pedido->status = "ABERTO";



        // deleta itens
        $deletar = $request->input('deletar');
        // dd($deletar);
        if(isset($deletar)){
            for($i = 0; $i < sizeof($deletar); $i++){
                $itemDeletado = ItensPedido::find(intval($deletar[$i]['id']));
                if(isset($itemDeletado)){

                    $valorTotal -= floatval($itemDeletado['valorReal']);
                    // dd($valorTotal);
                    $itemDeletado->delete();
                }
            }
        }
        $itens_pedidos = $request->input('itens_pedidos');
        if(isset($itens_pedidos)){
            foreach($request->input('itens_pedidos') as $item){
                $itemPedido = ItensPedido::find($item['id']);

                $produto = Produto::find($item['produto_id']);

                if(isset($produto)){
                    // dd(floatVal($item['pesoSolicitado']));
                    // $valorTotal += $produto->preco * floatVal($item['pesoSolicitado']);
                    // dd($valorTotal);
                    $itemPedido->pesoSolicitado = floatval($item['pesoSolicitado']);

                    $itemPedido->valorReal = $produto->preco * floatVal($item['pesoSolicitado']);

                    $itemPedido->save();
                }
            }
        }

        // Salva o valor total


        $pedido->valorTotal = floatval($valorTotal);
        // dd($valorTotal);
        $pedido->save(); // salva o pedido
        return route('listarPedidos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $pedido = Pedido::find($id);

        if(isset($pedido)){
            $itensPedido = ItensPedido::where("pedido_id",$id)->delete();
            $pagamento = Pagamento::where('pedido_id',$id)->delete();
            $pedido->delete();
            return response("Pedido Excluído",200);

        }else{
            return response('Pedido não encontrado',404);
        }
    }
    /**
     * Função que carrega os dados do pedido na view para salvar o peso dos itens
     * @param $id
     * @return view pesarPedido
     */
    public function pesarPedido($id){
        $pedido = Pedido::with(['itensPedidos'])->find($id);
        if(isset($pedido)){
            for($i = 0; $i< count($pedido->itensPedidos); $i++){
                $produto = Produto::find($pedido->itensPedidos[$i]->produto_id);
                //dd($produto);
                $pedido->itensPedidos[$i]["precoProduto"] = $produto->preco;
            }
            $cliente = Cliente::with('user')->find($pedido->cliente_id);
            $funcionario = Funcionario::with('user')->find($pedido->funcionario_id);

            if(isset($cliente->user->name)){
                $pedido["nomeCliente"] = $cliente->user->name;
            }
            else{
                $cliente = \App\Cliente::withTrashed()->find($pedido->cliente_id);
                $user = \App\User::withTrashed()->find($cliente->user_id);
                $pedido["nomeCliente"] = $user->name;
            }
            $pedido["nomeFuncionario"] = $funcionario->user->name;
            // $pedido["dataEntrega"] = new DateTime($pedido->dataEntrega);

            return view('finalizarPedido')->with(["pedido"=>$pedido]);
        }

    }
    /**
     * Função busca os dados referente ao pedido e retorna para tela concluirPedido
     * @param $id
     * @return view concluirPedido
     */
    public function concluirPedido($id){
        $pedido = Pedido::with(['itensPedidos'])->find($id);
        // dd($pedido);
        if(isset($pedido)){
            for($i = 0; $i< count($pedido->itensPedidos); $i++){
                $produto = Produto::find($pedido->itensPedidos[$i]->produto_id);
                $pedido->itensPedidos[$i]["precoProduto"] = floatval($produto->preco);
            }
            $cliente = Cliente::with('user')->find($pedido->cliente_id);
            $funcionario = Funcionario::with('user')->find($pedido->funcionario_id);

            // $pedido["valorProduto"]= $produto->preco;
            $pedido["nomeCliente"] = $cliente->user->name;
            $pedido["nomeFuncionario"] = $funcionario->user->name;
            // $pedido["dataEntrega"] = new DateTime($pedido->dataEntrega);

            return view('concluirPedido')->with(["pedido"=>$pedido]);
        }
    }

    /**
     * Função que calcula os descontos nos itens e retorna um array com o preço dos itens após
     * o desconto ser aplicado
     * @param Array $itens
     * @param Array $descontos
     * @return Array $itensComDesconto
     */
    function itensComDesconto($itens, $descontos){
        $itensComDesconto = [];
        for($i = 0; $i < count($itens); $i++){
            $itensComDesconto[$i] = floatval($itens[$i]->valorReal) - (floatval($itens[$i]->valorReal) * ($descontos[$i] / 100));
        }
        return $itensComDesconto;
    }
    /**
     * Salva os dados dos descontos referente aos itens e salva informações do pedido
     * @param Request
     * @return view pagamentos
     */
    public function concluirPedidoComDescontoNosItens(Request $request){
        // dd($request->all());
        // pedido
        $pedido = Pedido::find($request['pedido_id']);
        // array com a porcentagem dos descontos referente aos itens
        $descontos = $request['desconto'];
        // array com os itens do pedido
        $itensPedido = ItensPedido::where('pedido_id',$request['pedido_id'])->get();
        // array contendo os preços com os descontos calculados
        $itensComDesconto = $this->itensComDesconto($itensPedido, $descontos);


        // Se o pedido tiver o status PESADO
        if($pedido->status->status == "PESADO"){
            /**
             * Percorre $itensPedido, $descontos e $itensComDesconto
             * e salva o descontoPorcentagem e valorComDesconto
             */
            if(isset($descontos)){
                if(count($itensPedido) === count($itensComDesconto)){
                    for($i = 0; $i <= count($itensPedido) - 1; $i++ ){
                        $itensPedido[$i]->descontoPorcentagem = floatval($descontos[$i]);
                        $itensPedido[$i]->valorComDesconto = floatval($itensComDesconto[$i]);
                        $itensPedido[$i]->save();
                    }
                }
            }
            $pedido->save();
        }

        // ------------------------ DEBUG ------------------------
        // dd($request->all(),$pedido->status->status,$itensPedido,$itensComDesconto);
        // return view('pagamento',['pedido'=>$pedido]);

        return redirect('/pedidos/pagamento/'.$pedido->id);

    }

    /**
     * Função que calcula o desconto aplicado em cada forma de pagamento
     *
     * @param $valorTotalPagamento
     * @param $descontoPagamento
     * @return $valorPago
     */
    function descontoFormaPagamento($valorTotalPagamento, $descontoPagamento){
        $valorPago = 0.0;
        $valorPago = floatval($valorTotalPagamento) - (floatval($valorTotalPagamento) * floatval($descontoPagamento / 100));
        return $valorPago;
    }
    /**
     * Função que registra o pagamento efetuado
     * @param Request
     * @return void
     */
    public function pagamento(Request $request){
        // dd($request->all());
        $pedido = Pedido::find($request['pedido_id']);

        // -------------DEBUG----------------
        // dd($request->all(),$pedido,Auth::user()->funcionario->id);

        $pedido->entregador_id = $request['entregador_id'];
        $status_id = Status::where('status','ENTREGUE')->pluck('id')->first();
        $pedido->status_id = $status_id;
        $pedido->save();

        //Salva cada forma de pagamento
        for($i = 0; $i < count($request['formaPagamento']); $i++){
            $pagamento = new Pagamento();
            $pagamento->dataVencimento = $request['dataVencimento'][$i];
            // $pagamento->dataPagamento = $request['dataPagamento'][$i];
            $pagamento->obs = $request['obs'][$i];
            $pagamento->descontoPagamento = floatval($request['descontoPagamento'][$i]);//porcentagem do pagamento
            $pagamento->valorTotalPagamento = floatval($request['valorTotalPagamento'][$i]);//valor sem desconto aplicado
            // $pagamento->valorPago = self::descontoFormaPagamento(floatval($request['valorTotalPagamento'][$i]),floatval($request['descontoPagamento'][$i])); // valor com desconto aplicado
            $pagamento->valorPago = 0;
            $pagamento->status = "aberto";
            $pagamento->formaPagamento_id = $request['formaPagamento'][$i];

            $pagamento->funcionario_id = Auth::user()->funcionario->id;
            $pagamento->pedido_id = $pedido->id;
            $pagamento->save();
        }

        return redirect()->route('listarPedidos');

    }


    // retorna o cliente através do cpj ou cnpj
    public function getCliente(Request $request){
        $filtro = $request['filtro'];
        if($filtro == 'nome'){
            $cliente = User::with(['cliente'])->where('name','like','%'.$request->input('nome').'%')->get();
        }else{
            $cliente = Cliente::where('nomeReduzido','like','%'.$request->input('nome').'%')->get();
        }

        if(isset($cliente)){
            // dd($cliente);
            return json_encode($cliente);
        }
        else{
            return response('Cliente não encontrado', 404);
        }
    }

    public function buscaCliente($id){
        // dd($id);
        $c = Cliente::with(['user'])->find($id);
        // dd($cliente);
        $cliente['id'] = $c->id;
        $cliente['nome'] = $c->user->name;
        $cliente['nomeReduzido'] = $c->nomeReduzido;
        return json_encode($cliente);
    }
    public function getProdutos(Request $request){
        $produtos = Produto::where('nome','like','%'.$request->input('nome').'%')->get();
        // dd($produtos);
        if(isset($produtos)){
            return json_encode($produtos);
        }
        else{
            return response('Produto não encontrado', 404);
        }
    }
    function calcularTotal($listaProdutos){
        $valorTotal = 0.0;
        foreach($listaProdutos as $item){
            $produto = Produto::find($item[0]['produto_id']);
            if(isset($produto)){
                $valorTotal += floatval($produto->preco * $item[0]['peso']);
            }
        }
        return $valorTotal;
    }

    /**
     * Função para salvar pedido realizada
     * @param Request
     * @return JSON
     */
    public function finalizarPedido(Request $request){
        $cliente = Cliente::find($request->input('cliente_id'));


        // valor total sem desconto
        $valorTotal = 0.0;
        $desconto = 0.0;
        foreach($request->input('listaProdutos') as $item){
            $produto = Produto::find($item[0]['produto_id']);
            if(isset($produto)){
                $valorTotal += floatval($produto->preco * $item[0]['peso']);
            }
        }
        $pedido = new Pedido();

        //Tipo do Pedido
        $pedido->tipo = 'p';

        // valcula o desconto no valor total
        $pedido->valorTotal = $valorTotal;

        //dd($pedido);


        // $pedido->desconto = floatval($request->input('valorDesconto'));
        $pedido->dataEntrega = $request->input('dataEntrega');
        $status = Status::where('status','SOLICITADO')->first(); // Solicitado
        // dd($status->id);
        $pedido->status_id = $status->id;
        $pedido->cliente_id = $cliente->id;
        $user = User::find(Auth::user()->id);
        $funcionario = Funcionario::where('user_id','=', $user->id)->get();
        //dd($funcionario[0]->id);
        $pedido->funcionario_id = $funcionario[0]->id; //salvando o user_id do funcionario que está logado

        // dd($pedido);
        $pedido->save(); // salva o pedido

        foreach($request->input('listaProdutos') as $item){

            $itemPedido = new ItensPedido();
            $produto = Produto::find($item[0]['produto_id']);
            if(isset($produto)){
                $itemPedido->pesoSolicitado = $item[0]['peso'];
                $itemPedido->pesoFinal = $item[0]['peso'];
                $itemPedido->valorReal = floatval($produto->preco * $item[0]['peso']);
                $itemPedido->nomeProduto = $produto->nome;
                $itemPedido->produto_id = $produto->id;
                $itemPedido->pedido_id = $pedido->id;

                $itemPedido->save();
            }
        }

        return json_encode(['success'=> true,'msg'=>'Pedido cadastrado com sucesso']);
    }

    public function getPedidos(){
        $pedidos = Pedido::with(['itensPedidos'])->orderBy('status_id')->orderBy('dataEntrega')->get();
        $size = sizeof($pedidos);
        for($i = 0; $i < $size; $i++){
            $cliente = Cliente::with('user')->find($pedidos[$i]->cliente_id);
            $funcionario = Funcionario::with('user')->find($pedidos[$i]->funcionario_id);

            $pedidos[$i]["nomeCliente"] = $cliente->user->name;
            $pedidos[$i]["nomeFuncionario"] = $funcionario->user->name;
        }
        return json_encode($pedidos);

    }

    function calcularDescontosItens($itens, $descontos){
        $itensComDesconto = [];
        for($i = 0; $i < count($itens); $i++){
            $itensComDesconto[$i] = floatval($itens[$i]->valorReal) - (floatval($itens[$i]->valorReal) * ($descontos[$i] / 100));
        }
        return $itensComDesconto;
    }

    /**
     * Conclui a pesagem dos itens do pedido e salva os dados
     *
     * @param $request
     * @return view listarPedidos
     */
    public function concluirPedidoPesoFinal(Request $request){
        // dd($request->all());
        $pedido = Pedido::with(['itensPedidos'])->find($request->input('pedido_id'));
        $valorTotal = 0;
        foreach($pedido->itensPedidos as $item){
            $validator = $request->validate([
                'pesoFinal'.$item->id => 'required',
            ]);

            $produto = Produto::find($item->produto_id);
            $item->pesoFinal = floatval($request->input('pesoFinal'.$item->id));
            $item->valorReal = floatval($item->pesoFinal * $produto->preco);
            $valorTotal += floatval($item->valorReal);
            $item->save();
        }
        $pedido->valorTotal = floatval($valorTotal);
        $status = Status::where('status','PESADO')->first(); //
        $pedido->status_id = $status->id;

        // dd($pedido);
        $pedido->save();
        return redirect()->route('listarPedidos');
    }


    // Filtra Pedido
    public function filtrarPedido(Request $request, Pedido $pedido){
        $filtro = $request->all();
        // dd($filtro);

        if(isset($filtro['status_id'])){
            
            $pedidos = Pedido::with(['status','pagamento'])->where('tipo','p')->where('status_id',intval($filtro['status_id']))
                ->orderBy('status_id')->orderBy('dataEntrega')->paginate(25);
            
            $pedidosEntregues = Pedido::with(['status','pagamento'])->
                where('tipo','p')->
                where('status_id',3)->//ENTREGUE
                orderBy('status_id')->
                orderBy('dataEntrega')->paginate(25);

            return view('listarPedido',['pedidos'=>$pedidos,'pedidosEntregues'=>$pedidosEntregues,'filtro'=>$filtro,'achou'=> true,'tipoFiltro'=>"Status"]);
        }
        else if(isset($filtro['cliente'])){
            $id_users = User::where('name','LIKE','%'.strtoupper($filtro['cliente']).'%')->get('id');
            if(isset($id_users)){
                $id_clientes = Cliente::whereIn('user_id',$id_users)->get('id');
                // $pedidos = Pedido::where('tipo','p')
                //     ->orderBy('status_id')->orderBy('dataEntrega')->paginate(25);

                $pedidos = Pedido::with(['status','pagamento'])->
                    whereIn('cliente_id',$id_clientes)->
                    where('tipo','p')->
                    where(function($query){
                        $query->where('status_id',1)->//SOLICITADO
                                orWhere('status_id',2);//PESADO
                    })->
                    orderBy('status_id')->
                    orderBy('dataEntrega')->paginate(25);
                
                $pedidosEntregues = Pedido::with(['status','pagamento'])->
                    whereIn('cliente_id',$id_clientes)->
                    where('tipo','p')->
                    where('status_id',3)->//ENTREGUE
                    orderBy('status_id')->
                    orderBy('dataEntrega')->paginate(25);
                return view('listarPedido',['pedidos'=>$pedidos,'pedidosEntregues'=>$pedidosEntregues,'filtro'=>$filtro,'achou'=> true,'tipoFiltro'=>"Nome do Cliente"]);
            }else{
                return view('listarPedido',['pedidos'=>[],'pedidosEntregues'=>[],'filtro'=>$filtro,'achou'=> true,'tipoFiltro'=>"Nome do Cliente"]);
            }
        }
        else if(isset($filtro['nomeReduzido'])){

            $id_cliente = Cliente::where('nomeReduzido','LIKE','%'.strtoupper($filtro['nomeReduzido']).'%')->get('id');
            if(isset($id_cliente)){
                // $pedidos = Pedido::whereIn('cliente_id',$id_cliente)->where('tipo','p')
                //     ->orderBy('status_id')->orderBy('dataEntrega')->paginate(25);
                $pedidos = Pedido::with(['status','pagamento'])->
                    whereIn('cliente_id',$id_cliente)->
                    where('tipo','p')->
                    where(function($query){
                        $query->where('status_id',1)->//SOLICITADO
                                orWhere('status_id',2);//PESADO
                    })->
                    orderBy('status_id')->
                    orderBy('dataEntrega')->paginate(25);
                
                $pedidosEntregues = Pedido::with(['status','pagamento'])->
                    whereIn('cliente_id',$id_cliente)->
                    where('tipo','p')->
                    where('status_id',3)->//ENTREGUE
                    orderBy('status_id')->
                    orderBy('dataEntrega')->paginate(25);
                return view('listarPedido',['pedidos'=>$pedidos,'pedidosEntregues'=>$pedidosEntregues,'filtro'=>$filtro,'achou'=> true,'tipoFiltro'=>"Nome Reduzido"]);
            }
            else{
                return view('listarPedido',['pedidos'=>[],'pedidosEntregues'=>[],'filtro'=>$filtro,'achou'=> true,'tipoFiltro'=>"Nome Reduzido"]);
            }
        }
        else if(isset($filtro['dataEntregaInicial']) && !isset($filtro['dataEntregaFinal'])){
            // $pedidos = Pedido::whereDate('dataEntrega','>=',$filtro['dataEntregaInicial'])
            //                     ->where('tipo','p')->orderBy('status_id')->orderBy('dataEntrega')->paginate(25);
            $pedidos = Pedido::with(['status','pagamento'])->
                    whereDate('dataEntrega','>=',$filtro['dataEntregaInicial'])->
                    where('tipo','p')->
                    where(function($query){
                        $query->where('status_id',1)->//SOLICITADO
                                orWhere('status_id',2);//PESADO
                    })->
                    orderBy('status_id')->
                    orderBy('dataEntrega')->paginate(25);
                
            $pedidosEntregues = Pedido::with(['status','pagamento'])->
                    whereDate('dataEntrega','>=',$filtro['dataEntregaInicial'])->
                    where('tipo','p')->
                    where('status_id',3)->//ENTREGUE
                    orderBy('status_id')->
                    orderBy('dataEntrega')->paginate(25);
            return view('listarPedido',['pedidos'=>$pedidos,'pedidosEntregues'=>$pedidosEntregues,'filtro'=>$filtro,'achou'=> true,'tipoFiltro'=>"Data Entrega Maior ou Igual à: ".date('d/m/Y',strtotime($filtro['dataEntregaInicial']))]);
        }
        else if(!isset($filtro['dataEntregaInicial']) && isset($filtro['dataEntregaFinal'])){
            // $pedidos = Pedido::whereDate('dataEntrega','<=',$filtro['dataEntregaFinal'])->where('tipo','p')
            //     ->orderBy('status_id')->orderBy('dataEntrega')->paginate(25);
            $pedidos = Pedido::with(['status','pagamento'])->
                    whereDate('dataEntrega','<=',$filtro['dataEntregaFinal'])->
                    where('tipo','p')->
                    where(function($query){
                        $query->where('status_id',1)->//SOLICITADO
                                orWhere('status_id',2);//PESADO
                    })->
                    orderBy('status_id')->
                    orderBy('dataEntrega')->paginate(25);
                
            $pedidosEntregues = Pedido::with(['status','pagamento'])->
                    whereDate('dataEntrega','<=',$filtro['dataEntregaFinal'])->
                    where('tipo','p')->
                    where('status_id',3)->//ENTREGUE
                    orderBy('status_id')->
                    orderBy('dataEntrega')->paginate(25);
            return view('listarPedido',['pedidos'=>$pedidos,'pedidosEntregues'=>$pedidosEntregues,'filtro'=>$filtro,'achou'=> true,'tipoFiltro'=>"Data Entrega Menor ou Igual à: ".date('d/m/Y',strtotime($filtro['dataEntregaFinal']))]);
        }
        else if(isset($filtro['dataEntregaInicial']) && isset($filtro['dataEntregaFinal'])){
            $pedidos = Pedido::whereDate('dataEntrega','>=',$filtro['dataEntregaInicial'])->where('tipo','p')
                ->whereDate('dataEntrega','<=',$filtro['dataEntregaFinal'])
                ->orderBy('status_id')->orderBy('dataEntrega')->paginate(25);

            $pedidos = Pedido::with(['status','pagamento'])->
                    whereDate('dataEntrega','>=',$filtro['dataEntregaInicial'])->where('tipo','p')
                    ->whereDate('dataEntrega','<=',$filtro['dataEntregaFinal'])->
                    where(function($query){
                        $query->where('status_id',1)->//SOLICITADO
                                orWhere('status_id',2);//PESADO
                    })->
                    orderBy('status_id')->
                    orderBy('dataEntrega')->paginate(25);
            
            $pedidosEntregues = Pedido::with(['status','pagamento'])->
                        whereDate('dataEntrega','>=',$filtro['dataEntregaInicial'])->where('tipo','p')
                        ->whereDate('dataEntrega','<=',$filtro['dataEntregaFinal'])->
                        where('status_id',3)->//ENTREGUE
                        orderBy('status_id')->
                        orderBy('dataEntrega')->paginate(25);
            return view('listarPedido',['pedidos'=>$pedidos,'filtro'=>$filtro,'achou'=> true,'tipoFiltro'=>"Intervalo Data Entrega: ".date('d/m/Y',strtotime($filtro['dataEntregaInicial']))." e ".date('d/m/Y',strtotime($filtro['dataEntregaFinal']))]);
        }
        else{
            return redirect()->route("listarPedidos");
        }

        // // $pedidos = $pedido->filtro($filtro,25);
        // return view('listarPedido',['pedidos'=>$pedidos,'filtro'=>$filtro,'achou'=> true]);

    }


    public function removerProdutoItem($id){

        dd($id);
        $item = ItensPedido::where('id', '=', $id)->get();
        dd($item);
        return view('editarPedido');;

    }



}
