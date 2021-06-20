<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Cargo;

class CargoController extends Controller
{

    public function indexView()
    {   
        $cargos = Cargo::orderby('nome')->paginate(25);
        return view('cargo',['cargos'=>$cargos]);
    }

    public function buscarCargo(Request $request){
        $c =  strtoupper($request->input('q'));
        // dd($c);
        
        if(isset($c)){
            $cargos = Cargo::where('nome','LIKE','%'.$c.'%')
            ->paginate(10)->setpath('');
            $cargos->appends(array('q'=>$request->input('q')));
            if(count($cargos) > 0){
                // dd($cargos);
                return view('cargo',['cargos'=>$cargos, 'achou'=> true]);
            }else{
                return view('cargo')->withMenssage("Desculpa, não foi possível encontrar este cargo.");
            }
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cargos = Cargo::all();
        return json_encode($cargos);
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

        $cargo = new Cargo();
        $cargo->nome = strtoupper($request->input('nome'));
        $cargo->save();

        return json_encode($cargo);
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
        $cargo = Cargo::find($id);
        if(isset($cargo)){
            return json_encode($cargo);

        }
        else{
            return response('Cargo não encontrada',404);
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
        
        $cargo = Cargo::find($id);
        if(isset($cargo)){
            $cargo->nome = strtoupper($request->input('nome'));
            $cargo->save();
            return  json_encode($cargo);

        }
        else{
            return response('Cargo não encontrada',404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        $cargo = Cargo::find($id);
        if(isset($cargo)){
            $cargo->delete();
            return  response("OK",200);

        }
        else{
            return response('Cargo não encontrada',404);
        }
    }
}
