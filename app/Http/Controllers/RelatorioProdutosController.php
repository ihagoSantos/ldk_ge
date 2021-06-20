<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Produto;
use Illuminate\Http\Request;

class RelatorioProdutosController extends Controller
{
    //

    public function RelatorioProduto(){
        $view = 'relatorioProduto';

        $produtos = Produto::join('categorias', 'produtos.categoria_id', '=', 'categorias.id')
            ->select('produtos.id','produtos.nome', 'produtos.preco', 'produtos.validade', 'produtos.categoria_id')
            //->select('categorias.nome')
            ->get();
        $count = count($produtos);
        //dd($soma);
        #$categorias = Categoria::all();



        //$produto = Produto::select('id','nome', 'validade', 'preco')
        //     ->where('categoria_id', '=', $categoria->id)->get();







        $date = date('d/m/Y');
        $view = \View::make($view, compact('produtos','count',  'date'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view)->setPaper('a4', 'landscape');

        $filename = 'relatorioProduto'.$date;


        return $pdf->stream($filename.'.pdf');
    }



}
