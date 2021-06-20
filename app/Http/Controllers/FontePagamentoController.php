<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FontePagamento;

class FontePagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $fontePagamento = FontePagamento::paginate(25);
        return view('fontePagamento',['fontePagamento'=>$fontePagamento]);
    }

    public function buscarFontePagamento(Request $request){
        
        $c =  strtoupper($request->input('q'));
        // dd($c);
        
        if(isset($c)){
            $fontePagamento = FontePagamento::where('nome','LIKE','%'.$c.'%')
            ->paginate(10)->setpath('');
            $fontePagamento->appends(array('q'=>$request->input('q')));
            if(count($fontePagamento) > 0){
                // dd($fontePagamento);
                return view('fontePagamento',['fontePagamento'=>$fontePagamento, 'achou'=> true]);
            }else{
                return view('fontePagamento')->withMenssage("Desculpa, não foi possível encontrar esta Fonte de Pagamento.");
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
        //

        $validator = $this->validate($request,[
            'nome' => 'required|string|min:5',
            'agencia' => 'required|string|min:5',
            'conta' => 'required|string|min:5',
            'obs' => 'nullable|string|min:5',
        ]);

        $dados = $request->all();
        $fontePagamento = new FontePagamento();
        $fontePagamento->nome = strtoupper($dados['nome']);
        $fontePagamento->agencia = $dados['agencia'];
        $fontePagamento->conta = $dados['conta'];
        $fontePagamento->obs = $dados['obs'];
        $fontePagamento->save();

        return json_encode($fontePagamento);
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
        $fontePagamento = FontePagamento::find($id);
        return json_encode($fontePagamento);
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
        //
        $validator = $this->validate($request,[
            'nome' => 'required|string|min:5',
            'agencia' => 'required|string|min:5',
            'conta' => 'required|string|min:5',
            'obs' => 'nullable|string|min:5',
        ]);
        
        $fontePagamento = FontePagamento::find($id);
        if(isset($fontePagamento)){
            $dados = $request->all();
            $fontePagamento->nome = strtoupper($dados['nome']);
            $fontePagamento->agencia = $dados['agencia'];
            $fontePagamento->conta = $dados['conta'];
            $fontePagamento->obs = $dados['obs'];
            $fontePagamento->save();
            return json_encode($fontePagamento);
        }else{
            return response('Fonte de Pagamento não encontrada',404);
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
        $fontePagamento = FontePagamento::find($id);
        if(isset($fontePagamento)){
            $fontePagamento->delete();
            return  response("OK",200);
        }
        else{
            return response('Fonte de Pagamento não encontrada',404);
        }

    }
}
