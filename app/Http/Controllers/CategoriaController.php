<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Categoria;

class CategoriaController extends Controller
{
    // Retorna a view dos categoria
    public function indexView()
    {
        $categorias = Categoria::orderby('nome')->paginate(10);
        return view('categoria',["categorias"=>$categorias]);
    }
    public function index(Request $request)
    {
        $cats = Categoria::all();
        return json_encode($cats);
    }

    public function buscarCategoria(Request $request){
        $c =  strtoupper($request->input('q'));

        if(isset($c)){
            $categorias = Categoria::where('nome','LIKE','%'.$c.'%')
                            ->paginate(10)->setpath('');
            $categorias->appends(array('q'=>$request->input('q')));
            if(count($categorias) > 0){
                // dd($categorias);

                return view('categoria',['categorias'=>$categorias, 'achou'=> true]);
            }else{
                return view('categoria')->withMenssage("Desculpa, não foi possível encontrar esta categoria.");
            }
        }
    }
    public function adicionar(Request $request)
    {

        $categoria = new \LudkeLaravel\Categoria();
        $categoria->fill($request->all());
        $categoria->save();
        return view('categoria');
    }

    public function create()
    {

    }


    public function store(Request $request)
    {
        $validator = $this->validate($request,[
            'nome' => 'required|string|min:5',
        ]);


        $categoria = new Categoria();
        $categoria->nome = strtoupper($request->input('nome'));
        $categoria->save();

        //retorna o objeto para exibir na tabela
        return json_encode($categoria);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cat = Categoria::find($id);
        if(isset($cat)){
            return json_encode($cat);
        }
        else{
            return response('Categoria não encontrada',404);
        }
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $validator = $this->validate($request,[
            'nome' => 'required|string|min:5',
        ]);

        $cat = Categoria::find($id);
        // dd($cat);
        if(isset($cat)){
            $cat->nome = strtoupper($request->input('nome'));
            $cat->save();
            return json_encode($cat);
        }
        else{
            return response('Categoria não encontrada',404);
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
        $cat = Categoria::find($id);
        if(isset($cat)){
            $cat->delete();
            return response('OK',200);
        }
        return response('Categoria não encontrada',404);
    }

    // Essa função é usada para retornar as categorias para exibir na tela de produtos
    // public function indexJson(){
    //     $cats = Categoria::all();
    //     return json_encode($cats);
    // }

}
