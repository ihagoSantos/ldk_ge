<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CentroCusto;

class CentroCustoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $centroCusto = CentroCusto::paginate(25);
        
        return view('centroCusto',['centroCusto'=>$centroCusto]);
    }

    public function buscarCentroCusto(Request $request){
        $c =  strtoupper($request->input('q'));

        if(isset($c)){
            $centroCusto = CentroCusto::where('nome','LIKE','%'.$c.'%')
                            ->paginate(10)->setpath('');
            $centroCusto->appends(array('q'=>$request->input('q')));
            if(count($centroCusto) > 0){

                return view('centroCusto',['centroCusto'=>$centroCusto, 'achou'=> true]);
            }else{
                return view('centroCusto')->withMenssage("Desculpa, não foi possível encontrar este centro de custo.");
            }
        }
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
        $validator = $this->validate($request,[
            'nome' => 'required|string|min:5',
        ]);

        $dados = $request->all();
        $centroCusto = new CentroCusto();
        $centroCusto->nome = $dados['nome'];
        $centroCusto->obs = $dados['obs'];
        $centroCusto->save();

        return json_encode(['status'=>1, 'msg'=>'Centro de Custo cadastrado com sucesso']);
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
        $centroCusto = CentroCusto::find($id);
        if(isset($centroCusto)){
            return json_encode($centroCusto);
        }else{
            return response('Centro de Custo não encontrado',404);
        }
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
        $validator = $this->validate($request,[
            'nome' => 'required|string|min:5',
        ]);
        $dados = $request->all();
        $centroCusto = CentroCusto::find($id);
        if(isset($centroCusto)){
            
            $centroCusto->nome = $dados['nome'];
            $centroCusto->obs = $dados['obs'];
            $centroCusto->save();

            return json_encode($centroCusto);
        }else{
            return response('Centro de Custo não encontrado',404);
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
        $centroCusto = CentroCusto::find($id);
        if(isset($centroCusto)){
            $centroCusto->delete();
            return  response("OK",200);
        }else{
            return response('Centro de Custo não encontrado',404);
        }

    }
}
