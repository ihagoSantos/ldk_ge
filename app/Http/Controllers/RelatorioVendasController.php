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

class RelatorioVendasController extends Controller
{
    public function RelatorioGeral(Request $request){
        // Set variável filtro
        $filtro = $request->all();

        // filtra as vendas
        $vendas = self::filtrarVenda($filtro);

        $view = 'relatorioGeralVenda';
        // $vendas = Pedido::all();
        $total = 0;
        foreach ($vendas as $venda){
            $total += $venda->valorTotal;
        }
        $count = count($vendas);

        //dd($total);


        $date = date('d/m/Y');
        $view = \View::make($view, compact('vendas', 'total','count','date'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view)->setPaper('a4', 'landscape');

        $filename = 'relatorioGeralVendas'.$date;


        return $pdf->stream($filename.'.pdf');
    }


    // Filtra Vendas
    public function filtrarVenda($filtro){
        // dd(strtoupper($filtro['filtroRelatorioVendasNomeCliente']));
        $vendas = [];
        if(isset($filtro['filtroRelatorioVendasStatus_id'])){
            $vendas = Pedido::where('status_id',intval($filtro['filtroRelatorioVendasStatus_id']))->where(function($query){
                $query->where('tipo','v')->orWhere('tipo','vm');
            })->orderBy('status_id')->orderBy('dataEntrega')->get();
            return $vendas;
        }
        else if(isset($filtro['filtroRelatorioVendasNomeCliente'])){
            $id_users = User::where('name','like','%'.strtoupper($filtro['filtroRelatorioVendasNomeCliente']).'%')->get('id');
            if(isset($id_users)){
                $id_clientes = Cliente::whereIn('user_id',$id_users)->get('id');
                $vendas = Pedido::whereIn('cliente_id',$id_clientes)->where(function($query){
                    $query->where('tipo','v')->orWhere('tipo','vm');
                })->orderBy('status_id')->orderBy('dataEntrega')->get();
                return $vendas;
            }
        }
        else if(isset($filtro['filtroRelatorioVendasNomeReduzido'])){

            $id_clientes = Cliente::where('nomeReduzido','LIKE','%'.strtoupper($filtro['filtroRelatorioVendasNomeReduzido']).'%')->get('id');
            if(isset($id_clientes)){
                $vendas = Pedido::whereIn('cliente_id',$id_clientes)->where(function($query){
                    $query->where('tipo','v')->orWhere('tipo','vm');
                })->orderBy('status_id')->orderBy('dataEntrega')->get();
                return $vendas;
            }
            else{
                return $vendas;
            }
        }
        else if(isset($filtro['filtroRelatorioVendasDataEntregaInicial']) && !isset($filtro['filtroRelatorioVendasDataEntregaFinal'])){
            $vendas = Pedido::whereDate('dataEntrega','>=',$filtro['filtroRelatorioVendasDataEntregaInicial'])->where(function($query){
                $query->where('tipo','v')->orWhere('tipo','vm');
            })->orderBy('status_id')->orderBy('dataEntrega')->get();
                return $vendas;
        }
        else if(!isset($filtro['filtroRelatorioVendasDataEntregaInicial']) && isset($filtro['filtroRelatorioVendasDataEntregaFinal'])){
            $vendas = Pedido::whereDate('dataEntrega','<=',$filtro['filtroRelatorioVendasDataEntregaFinal'])->where(function($query){
                $query->where('tipo','v')->orWhere('tipo','vm');
            })->orderBy('status_id')->orderBy('dataEntrega')->get();
                return $vendas;
        }
        else if(isset($filtro['filtroRelatorioVendasDataEntregaInicial']) && isset($filtro['filtroRelatorioVendasDataEntregaFinal'])){
            $vendas = Pedido::whereDate('dataEntrega','>=',$filtro['filtroRelatorioVendasDataEntregaInicial'])
                ->whereDate('dataEntrega','<=',$filtro['filtroRelatorioVendasDataEntregaFinal'])->where(function($query){
                    $query->where('tipo','v')->orWhere('tipo','vm');
                })->orderBy('status_id')->orderBy('dataEntrega')->get();
                return $vendas;
        }
        else if(isset($filtro['filtroRelatorioVendasEntregador'])){
            $vendas = Pedido::where('entregador_id',$filtro['filtroRelatorioVendasEntregador'])->where(function($query){
                $query->where('tipo','v')->orWhere('tipo','vm');
            })->orderBy('status_id')->orderBy('dataEntrega')->get();
                return $vendas;
        }
        else{
            $vendas = Pedido::where('tipo','v')->orWhere('tipo','vm')->get();
            return $vendas;
        }

    }




    public function RelatorioVendas($id){
        $view = 'relatorioVenda';
        $pedido = Pedido::find($id);
        $itens = ItensPedido::where('pedido_id', '=', $pedido->id)->get();
        $clientes = Cliente::where('id', '=', $pedido->cliente_id)->get();
        $soma = 0;
        foreach ($itens as $iten){
            $soma += $iten->valorReal;
        }

        #####Soma
        $count = count($itens);

        #dd($soma);




        $date = date('d/m/Y');
        $view = \View::make($view, compact('itens', 'clientes','soma', 'count', 'date'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view)->setPaper('a6', 'landscape');

        $filename = 'relatorioVenda'.$date;


        return $pdf->stream($filename.'.pdf');
    }

}
