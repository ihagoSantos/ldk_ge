<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Telefone;
use App\Fornecedor;
 
class FornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $fornecedores = Fornecedor::paginate(25);
        return view('fornecedor',['fornecedores'=>$fornecedores]);
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
        
        // Validação
        $validator = $this->validate($request,[
            'nome' => 'required|string|min:5',
            'nomeResponsavel' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:fornecedors',
            'cpfCnpj' => 'required|string|max:255|unique:fornecedors',
            'email' => 'nullable|string|email|max:255|unique:fornecedors',
            'tipo' => 'required|string|max:255',
            'telefone' => 'required|string|max:255',
        ]);

        if($request['telefone']){
            $telefone = new Telefone();
            $telefone->residencial = $request['telefone'];
            $telefone->save();
        }

        $fornecedor = new Fornecedor();
        $fornecedor->nome = strtoupper($request['nome']);
        if($request['nomeResponsavel']) $fornecedor->nomeResponsavel = strtoupper($request['nomeResponsavel']);
        $fornecedor->cpfCnpj = $request['cpfCnpj'];
        if($request['email']) $fornecedor->email = $request['email'];
        $fornecedor->tipo = strtoupper($request['tipo']);

        if($request['telefone'] && $telefone->id) $fornecedor->telefone_id = $telefone->id;
        $fornecedor->save();

        return json_encode($fornecedor);
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
        $fornecedor = Fornecedor::with("telefone")->find($id);
        return json_encode($fornecedor);
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
        // Validação
        $validator = $this->validate($request,[
            'nome' => 'required|string|min:5',
            'nomeResponsavel' => 'nullable|string|max:255',
            
            // 'cpfCnpj' => 'required|string|max:255|unique:fornecedors',
            // 'email' => 'nullable|string|email|max:255|unique:fornecedors',
            'tipo' => 'required|string|max:255',
            'telefone' => 'required|string|max:255',
        ]);
        // Fornecedor
        $fornecedor = Fornecedor::find($id);
        $fornecedor->nome = strtoupper($request["nome"]);
        $fornecedor->nomeResponsavel = strtoupper($request["nomeResponsavel"]);
        $fornecedor->cpfCnpj = $request["cpfCnpj"];
        if($fornecedor->email != $request["email"]){
            $fornecedor->email = $request["email"];
        }
        $fornecedor->tipo = $request["tipo"];

        // Telefone
        if($fornecedor->telefone_id){

            $telefone = Telefone::find($fornecedor->telefone_id);
            $telefone->residencial = $request['telefone'];
            $telefone->save();
        }

        $fornecedor->save();

        return json_encode($fornecedor);

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
        $fornecedor = Fornecedor::find($id);
        if(isset($fornecedor)){
            $fornecedor->delete();
            return response('OK',200);
        }else{
            return response('Funcionario não encontrado',404);
        }


    }

    public function buscarFornecedor(Request $request){
        $forn = strtoupper($request['q']);

        if(isset($forn)){
            $fornecedores = Fornecedor::where('nome','LIKE','%'.$forn.'%')->paginate(10)->setpath('');
            return view('fornecedor',['fornecedores'=>$fornecedores, 'achou'=> true]);
        }
    }
}

