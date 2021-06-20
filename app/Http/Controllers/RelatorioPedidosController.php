<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use App\ItensPedido;
use App\Pedido;
use App\Cliente;
use App\User;
use App\Funcionario;
use App\Cargo;
class RelatorioPedidosController extends Controller
{
    //

    public function RelatorioSolicitado($id){

        $view = 'relatorioSolicitado';
        $pedido = Pedido::find($id);
        $itens = ItensPedido::where('pedido_id', '=', $pedido->id)->get();
        $clientes = Cliente::where('id', '=', $pedido->cliente_id)->get();

        $count = count($itens);

        //dd($count);

        //dd($pedido);


        $date = date('d/m/Y');
        $view = \View::make($view, compact('itens', 'clientes', 'pedido','count' ,'date'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view)->setPaper('a6', 'landscape');

        $filename = 'relatorioSolicitado'.$date;


        return $pdf->stream($filename.'.pdf');

    }

    public function RelatorioPedidos($id){
        $view = 'relatorioPedido';
        $pedido = Pedido::find($id);
        $itens = ItensPedido::where('pedido_id', '=', $pedido->id)->get();
        $clientes = Cliente::where('id', '=', $pedido->cliente_id)->get();
        $soma = 0;
        foreach ($itens as $iten){
            $soma += $iten->valorReal;
        }

        #####Soma

        $count = count($itens);


        $date = date('d/m/Y');
		$view = \View::make($view, compact('itens', 'clientes','soma', 'pedido','count', 'date'))->render();
		$pdf = \App::make('dompdf.wrapper');
		$pdf->loadHTML($view)->setPaper('a6', 'landscape');

		$filename = 'relatorioPedido'.$date;


		return $pdf->stream($filename.'.pdf');
    }



    public function RelatorioGeral(Request $request){
        // Set variável filtro
        $filtro = $request->all();

        // filtra os pedidos
        $pedidos = self::filtrarPedido($filtro);


        $entregador = null;
        $view = 'relatorioGeralPedido';
        // $pedidos = Pedido::all();
        $total = 0;

        $entregadores = array();

        foreach ($pedidos as $pedido){
            //dd($pedido->entregador_id);
            $total += $pedido->valorTotal;
            $entregadores[] = Funcionario::select('user_id')
            ->where('id','=', $pedido->entregador_id)->get();



        }
        //dd($entregadores);
        $final = array();
        foreach($entregadores as $entregador){
            foreach ($entregador as $name) {
                //dd($entregador);
                $user = User::select('name')
                    ->where('id', '=', $name->user_id)->get();
                $final [] = $user;
            }

        }


        //$user = User::where('id','=', $final[]->user_id);
        //dd($user);

        //dd($final[1][0]);
        //dd($final);
        $count = count($pedidos);

        //dd($total);


        $date = date('d/m/Y');
        $view = \View::make($view, compact('pedidos', 'total','count','final','date'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view)->setPaper('a4', 'landscape');

        $filename = 'relatorioGeralPedido'.$date;


        return $pdf->stream($filename.'.pdf');
    }


    // Filtra Pedido
    public function filtrarPedido($filtro){
        //dd($filtro);

        $pedidos = [];
        if(isset($filtro['filtroRelatorioStatus_id'])){
            $pedidos = Pedido::where('tipo','p')->where('status_id',intval($filtro['filtroRelatorioStatus_id']))
                ->orderBy('status_id')->orderBy('dataEntrega')->get();
            return $pedidos;
        }
        else if(isset($filtro['filtroRelatorioNomeCliente'])){
            $user = User::where('name','LIKE','%'.strtoupper($filtro['filtroRelatorioNomeCliente']).'%')->first();
            if(isset($user)){
                $cliente = Cliente::where('user_id',$user->id)->first();
                $pedidos = Pedido::where('cliente_id',$cliente->id)->where('tipo','p')
                    ->orderBy('status_id')->orderBy('dataEntrega')->get();
                    return $pedidos;
            }else{
                return view('listarPedido',['pedidos'=>[],'filtro'=>$filtro,'achou'=> true,'tipoFiltro'=>"Nome do Cliente"]);
            }
        }
        else if(isset($filtro['filtroRelatorioNomeReduzido'])){

            $cliente = Cliente::where('nomeReduzido','LIKE','%'.strtoupper($filtro['filtroRelatorioNomeReduzido']).'%')->first();
            if(isset($cliente)){
                $pedidos = Pedido::where('cliente_id',$cliente->id)->where('tipo','p')
                    ->orderBy('status_id')->orderBy('dataEntrega')->get();
                return $pedidos;
            }
            else{
                return $pedidos;
            }
        }
        else if(isset($filtro['filtroRelatorioDataEntregaInicial']) && !isset($filtro['filtroRelatorioDataEntregaFinal'])){
            $pedidos = Pedido::where('tipo','p')->whereDate('dataEntrega','>=',$filtro['filtroRelatorioDataEntregaInicial'])
                ->orderBy('status_id')->orderBy('dataEntrega')->get();
                return $pedidos;
        }
        else if(!isset($filtro['filtroRelatorioDataEntregaInicial']) && isset($filtro['filtroRelatorioDataEntregaFinal'])){
            $pedidos = Pedido::where('tipo','p')->whereDate('dataEntrega','<=',$filtro['filtroRelatorioDataEntregaFinal'])
                ->orderBy('status_id')->orderBy('dataEntrega')->get();
                return $pedidos;
        }
        else if(isset($filtro['filtroRelatorioDataEntregaInicial']) && isset($filtro['filtroRelatorioDataEntregaFinal'])){
            $pedidos = Pedido::where('tipo','p')->whereDate('dataEntrega','>=',$filtro['filtroRelatorioDataEntregaInicial'])
                ->whereDate('dataEntrega','<=',$filtro['filtroRelatorioDataEntregaFinal'])
                ->orderBy('status_id')->orderBy('dataEntrega')->get();
                return $pedidos;
        }
        else if(isset($filtro['filtroRelatorioEntregador'])){
            $pedidos = Pedido::where('tipo','p')->where('entregador_id',$filtro['filtroRelatorioEntregador'])->get();
                return $pedidos;
        }
        else{
            $pedidos = Pedido::where('tipo','p')->get();
            return $pedidos;
        }

    }

    public function getEntregadores(){
        $entregador_id = Cargo::where('nome','ENTREGADOR')->pluck('id')->first();
        $gerenteAdministrativo_id = Cargo::where('nome','GERENTE ADMINISTRATIVO')->pluck('id')->first();
        $gerenteGeral_id = Cargo::where('nome','GERENTE GERAL')->pluck('id')->first();
        $vendedor_id = Cargo::where('nome','VENDEDOR(A)')->pluck('id')->first();

        $entregadores = Funcionario::with(['user'])
            ->where('cargo_id',$entregador_id)
            ->orWhere('cargo_id',$gerenteAdministrativo_id)
            ->orWhere('cargo_id',$gerenteGeral_id)
            ->orWhere('cargo_id',$vendedor_id)
            ->get();
        return json_encode($entregadores);
    }
}
