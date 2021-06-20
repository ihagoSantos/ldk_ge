<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Produto;
use App\FotosProduto;
use App\Categoria;
use File;

class ProdutoController extends Controller
{
    // Retorna a view dos produtos
    public function indexView()
    {
        $produtos = Produto::orderby("nome")->paginate(25);
        return view('produto',["produtos"=>$produtos]);
    }
    public function buscarProduto(Request $request){
        $p = strtoupper($request->input('q'));

        if(isset($p)){
            $produtos = Produto::where('nome','LIKE','%'.$p.'%')
                        ->paginate(25)->setpath('');
            $produtos->appends(array('q'=>$request->input('q')));
            if(count($produtos)){
                return view('produto',['produtos'=>$produtos, 'achou'=>true]);
            }else{
                return view('produto')->withMenssage("Desculpa, não foi possível encontrar este produto.");
            }
        }
    }
    //usado pela api para retornar os produtos
    public function index()
    {
        $produtos = Produto::with(['categoria'])->get();
        return $produtos->toJson();
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


    // Recebe o request do ajax e salva produto com as fotos
    public function store(Request $request)
    {
        $validator = $this->validate($request,[
            'nome' => 'required|string|min:3|max:255',
            'validade' => 'required|string',
            'preco' => 'required',
            //'descricao' => 'nullable|string|min:5|max:255',
            'categoriaProduto' => 'required',
        ]);

        // salva produtos no banco
        $prod = new Produto();
        //$validade_data =
        $prod->nome = strtoupper($request->input('nome'));
        $prod->validade = $request->input('validade');
        //dd($prod->validade);
        // $prod->quantidade = $request->input('quantidade');
        $prod->preco = $request->input('preco');
        #dd($prod->preco);
        
        #$novo_preco = number_format($prod->preco, 2, '.', '');
        #dd($novo_preco);
        //$prod->descricao = strtoupper($request->input('descricao'));
        $prod->categoria_id = $request->input('categoriaProduto');



        #dd($prod->preco);

        $prod->save();

        $fotosProduto = $request->file('imagensProduto');
        if(isset($fotosProduto)){
            foreach($fotosProduto as $f){
                $path = $f->store('public');
                $nomeFoto = str_replace('public/','',$path);


                $foto = new FotosProduto();
                $foto->path = $nomeFoto;
                $foto->produto_id = $prod->id;
                $foto->save();
            }
        }

        $categoria = Categoria::find(intval($prod->categoria_id));
        $prod['categoria'] = ["nome"=>$categoria->nome];
        // retorna o objeto para exibir na tabela
        return json_encode($prod);


    }

    //Exibe um determinado produto
    public function show($id)
    {
        //dd("Ok");
        $produto = Produto::with('categoria')->find($id);
        $fotosProduto = FotosProduto::where('produto_id',$id)->get();
        // dd($fotosProduto);
        // dd($produto);
        $prod = [
            'id' => $produto->id,
            'nome' => $produto->nome,
            'validade' => $produto->validade,
            'preco' => $produto->preco,
            //'descricao' => $produto->descricao,
            'categoria_id' => $produto->categoria_id,
            'created_at' => $produto->created_at,
            'updated_at' => $produto->updated_at,
            'fotosProduto' => $fotosProduto,
            'categoria' => $produto->categoria,
        ];
        if(isset($prod)){
            return json_encode($prod);// retorna um objeto json
        }
        else{
            return response('Produto não encontrado',404);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    // Nova Função para atualizar o produto e foto
    public function updateProdWithImage(Request $request, $id){

        // dd($request->input('categoriaProduto'));

        $validator = $this->validate($request,[
            'nome' => 'required|string|min:3|max:255',
            'validade' => 'required|string',
            'preco' => 'required',
            //'descricao' => 'nullable|string|min:5|max:255',
        ]);
        // dd($request->all());
        $prod = Produto::find($id);
        // dd($request->input('nome'));

        if(isset($prod)){
            $prod->nome = strtoupper($request->input('nome'));
            $prod->validade = $request->input('validade');
            // $prod->quantidade = $request->input('quantidade');
            $prod->preco = $request->input('preco');
           // $prod->descricao = strtoupper($request->input('descricao'));
            $prod->categoria_id = $request->input('categoriaProduto');

            $fotosProduto = $request->file('imagensProduto');
            if(isset($fotosProduto)){
                foreach($fotosProduto as $f){
                    $path = $f->store('public');
                    $nomeFoto = str_replace('public/','',$path);
                    // $path = $f->store('fotosProduto');
                    // dd($path);
                    $foto = new FotosProduto();
                    $foto->path = $nomeFoto;
                    $foto->produto_id = $prod->id;
                    $foto->save();
                }
            }


            if(isset($request->arraytarFotos)){

                // array contendo o id das imagens para deletar
                $arrayIdsDeletarFoto = explode(',',$request->arrayIdsDeletarFotos);
                // dd(gettype($arrayIdsDeletarFoto));
                for($i = 0; $i < count($arrayIdsDeletarFoto);$i++){
                    $foto = FotosProduto::find($arrayIdsDeletarFoto[$i]);
                    // $foto->delete();

                    Storage::delete("public/{$foto->path}");
                    FotosProduto::destroy($foto->id);
                }

            }
            // dd($prod);
            $prod->save();
            $categoria = Categoria::find($prod->categoria_id);
            $prod['categoria'] = ["nome"=>$categoria->nome];

            // retorna o objeto para exibir na tabela
            return json_encode($prod);
        }
    }

    // public function update(Request $request, $id)
    // {
    //     $prod = Produto::find($id);
    //     // dd($request->input('nome'));

    //     if(isset($prod)){
    //         $prod->nome = $request->input('nome');
    //         $prod->validade = $request->input('validade');
    //         // $prod->quantidade = $request->input('quantidade');
    //         $prod->preco = $request->input('preco');
    //         $prod->descricao = $request->input('descricao');
    //         $prod->categoria_id = $request->input('categoria_id');

    //         $fotosProduto = $request->file('imagensProduto');
    //         if(isset($fotosProduto)){
    //             foreach($fotosProduto as $f){
    //                 $path = $f->store('public');
    //                 $nomeFoto = str_replace('public/','',$path);
    //                 // $path = $f->store('fotosProduto');
    //                 // dd($path);
    //                 $foto = new FotosProduto();
    //                 $foto->path = $nomeFoto;
    //                 $foto->produto_id = $prod->id;
    //                 $foto->save();
    //             }
    //         }

    //         if(isset($request->arrayIdsDeletarFotos)){
    //             foreach($request->arrayIdsDeletarFotos as $id){
    //                 $foto = FotosProduto::find($id);
    //                 if(isset($foto)){

    //                     // File::delete("public/".$foto->path);
    //                     // $foto->delete();

    //                     Storage::delete("public/{$foto->path}");
    //                     FotosProduto::destroy($foto->id);
    //                 }
    //             }
    //         }

    //         $prod->save();
    //         // retorna o objeto para exibir na tabela
    //         return json_encode($prod);
    //     }
    //     else{
    //         return response('Produto não encontrado',404);
    //     }
    // }


    // Função para deletar produto e foto
    public function destroy($id)
    {
        $prod = Produto::find($id);
        if(isset($prod)){
            $fotosProduto = FotosProduto::where('produto_id',$prod->id)->get();
            // $fotos = $fotosProduto;
            if(isset($fotosProduto)){
                foreach($fotosProduto as $foto){
                    Storage::delete("public/{$foto->path}");
                    FotosProduto::destroy($foto->id);

                }
            }
            $prod->delete();
            return response('Produto deletado com sucesso',200);
        }
        return response('Produto não encontrado',404);
    }
}
