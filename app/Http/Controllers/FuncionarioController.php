<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Cargo;
use App\Telefone;
use App\Endereco;
use App\Funcionario;

class FuncionarioController extends Controller
{
    // Retorna a view dos funcionarios
    public function indexView()
    {
        $funcionarios = Funcionario::paginate(25);
        return view('funcionario',['funcionarios'=>$funcionarios]);
    }
    public function buscarFuncionario(Request $request){
        $f =  strtoupper($request->input('q'));
        // dd($c);

        if(isset($f)){
            $users = User::where('name','LIKE','%'.$f.'%')->pluck('id');
            $funcionarios = Funcionario::whereIn('user_id',$users)->paginate(10)->setpath('');
            // dd($clientes);

            // ->paginate(10)->setpath('');
            $funcionarios->appends(array('q'=>$request->input('q')));
            if(count($funcionarios) > 0){
                // dd($cargos);
                return view('funcionario',['funcionarios'=>$funcionarios, 'achou'=> true]);
            }else{
                return view('funcionario')->withMenssage("Desculpa, não foi possível encontrar este funcionario.");
            }
        }

    }
    public function index()
    {


        $funcionarios = Funcionario::all();
        $arrayFuncionarios = Array();
        foreach($funcionarios as $f){
            $user = User::where('id',$f->user_id)->first();
            $endereco = Endereco::where('id',$user->endereco_id)->first();
            $telefone = Telefone::where('id',$user->telefone_id)->first();
            $cargo = Cargo::find($f->cargo_id);


           //console.log($f->cargo_id);
            $fun = [
                    'id' => $f->id,
                    'email' => $user->email,
                    'nome' => $user->name,
                    'cargo' => $f->cargo->nome,
                    'residencial' => $telefone->residencial,
                    'celular' => $telefone->celular,
                    'cep' => $endereco->cep,
                    'rua' => $endereco->rua,
                    'bairro' => $endereco->bairro,
                    'cidade' => $endereco->cidade,
                    'uf' => $endereco->uf,
                    'numero' => $endereco->numero,
                    'complemento' => $endereco->complemento,
                    ];
            array_push($arrayFuncionarios,$fun);
        }
        return json_encode($arrayFuncionarios);
        // $funcionario = Funcionario::find(1)->user;
        // return json_encode($funcionario);
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


    public function store(Request $request)
    {

        // Validação
        $validator = $this->validate($request,[
            'email' => 'required|string|email|max:255|unique:users',
            'nome' => 'required|string|min:5',
            'cargo' => 'required',
            'cep' => 'nullable|string',
            'rua' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'uf' => 'required',
            'numero' => 'required|string',
            'complemento' => 'nullable|string|max:255',
        ]);

        // ENDERECO
        $endereco = new Endereco();
        $endereco->rua = strtoupper($request->input('rua'));
        $endereco->numero = $request->input('numero');
        $endereco->bairro = strtoupper($request->input('bairro'));
        $endereco->cidade = strtoupper($request->input('cidade'));
        $endereco->uf = strtoupper($request->input('uf'));
        $endereco->cep = $request->input('cep');
        $endereco->complemento = strtoupper($request->input('complemento'));
        $endereco->save();

        // TELEFONE
        $telefone = new Telefone();
        $telefone->residencial = $request->input('residencial');
        $telefone->celular = $request->input('celular');
        $telefone->save();

        // USER
        $user = new User();
        $senhaAutomatica = bcrypt('123456');
        $user->name = strtoupper($request->input('nome'));
        $user->tipo = 'funcionario';
        $user->email= $request->input('email');
        $user->password = $senhaAutomatica;
        $user->endereco_id = $endereco->id;
        $user->telefone_id = $telefone->id;
        $user->save();

        $funcionario = new Funcionario();
        $funcionario->user_id = $user->id;
        $funcionario->cargo_id = $request->input('cargo');
        $funcionario->save();


        // $user = $user->toArray();
        // $endereco = $endereco->toArray();
        // $telefone = $telefone->toArray();

        $fun = [
            'id' => $funcionario->id,
            'email' => $user->email,
            'nome' => $user->name,
            'cargo' => $funcionario->cargo->nome,
            'residencial' => $telefone->residencial,
            'celular' => $telefone->celular,
            'cep' => $endereco->cep,
            'rua' => $endereco->rua,
            'bairro' => $endereco->bairro,
            'cidade' => $endereco->cidade,
            'uf' => $endereco->uf,
            'numero' => $endereco->numero,
            'complemento' => $endereco->complemento,
        ];
        // dd($fun);
        // var_dump($fun);
        return json_encode($fun);
        // Response::json(array('user'=>$user, 'endereco'=> $endereco, 'telefone'=>$telefone));

    }


    public function show($id)
    {
        $funcionario = Funcionario::find($id);

        $user = User::find($funcionario->user_id);
        $telefone = Telefone::find($user->telefone_id);
        $endereco = Endereco::find($user->endereco_id);
       // dd($funcionario->cardo_id);
        //console.log($cargo);
        if(isset($funcionario) && isset($user)
        && isset($telefone) && isset($endereco)){

            $fun = [
                'id' => $funcionario->id,
                'email' => $user->email,
                'nome' => $user->name,
                'cargo' => $funcionario->cargo->id,
                'residencial' => $telefone->residencial,
                'celular' => $telefone->celular,
                'cep' => $endereco->cep,
                'rua' => $endereco->rua,
                'bairro' => $endereco->bairro,
                'cidade' => $endereco->cidade,
                'uf' => $endereco->uf,
                'numero' => $endereco->numero,
                'complemento' => $endereco->complemento,
            ];

            return json_encode($fun);

        }
        else{
            return response('Funcionário não encontrado',404);
        }


    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $funcionario = Funcionario::find($id);
        $user = User::find($funcionario->user_id);
        $telefone = Telefone::find($user->telefone_id);
        $endereco = Endereco::find($user->endereco_id);

        if($user->email != $request->input('email')){
            // Validação
            $validator = $this->validate($request,[
                'email' => 'required|email',
                'nome' => 'required|string|min:5',
                'cargo' => 'required',
                'cep' => 'nullable|string',
                'rua' => 'required',
                'bairro' => 'required',
                'cidade' => 'required',
                'uf' => 'required',
                'numero' => 'required|string',
                'complemento' => 'nullable|string|max:255',
            ]);
        }else{
            // Validação
            $validator = $this->validate($request,[
                // 'email' => 'required|email',
                'nome' => 'required|string|min:5',
                'cargo' => 'required',
                'cep' => 'nullable|string',
                'rua' => 'required',
                'bairro' => 'required',
                'cidade' => 'required',
                'uf' => 'required',
                'numero' => 'required|string',
                'complemento' => 'nullable|string|max:255',
            ]);
        }





        if(isset($funcionario) && isset($user)
        && isset($telefone) && isset($endereco)){
            // ENDERECO
            $endereco->rua = $request->input('rua');
            $endereco->numero = $request->input('numero');
            $endereco->bairro = $request->input('bairro');
            $endereco->cidade = $request->input('cidade');
            $endereco->uf = $request->input('uf');
            $endereco->cep = $request->input('cep');
            $endereco->complemento = $request->input('complemento');
            $endereco->save();

            // TELEFONE
            $telefone->residencial = $request->input('residencial');
            $telefone->celular = $request->input('celular');
            $telefone->save();

            // USER
            $user->name = strtoupper($request->input('nome'));
            $user->email= $request->input('email');
            $user->save();

            $funcionario->user_id = $user->id;
            $funcionario->cargo_id = $request->input('cargo');
            $funcionario->save();



        $fun = [
            'id' => $funcionario->id,
            'email' => $user->email,
            'nome' => $user->name,
            'cargo' => $funcionario->cargo_id,
            'residencial' => $telefone->residencial,
            'celular' => $telefone->celular,
            'cep' => $endereco->cep,
            'rua' => $endereco->rua,
            'bairro' => $endereco->bairro,
            'cidade' => $endereco->cidade,
            'uf' => $endereco->uf,
            'numero' => $endereco->numero,
            'complemento' => $endereco->complemento,
        ];

            return json_encode($fun);

        }
        else{
            return response('Funcionário não encontrado',404);
        }
    }


    public function destroy($id)
    {
        $funcionario = Funcionario::find($id);
        $user = User::find($funcionario->user_id);
        $telefone = Telefone::find($user->telefone_id);
        $endereco = Endereco::find($user->endereco_id);
        if(isset($funcionario)){
            $funcionario->delete();
            $user->delete();
            $telefone->delete();
            $endereco->delete();
            return response('OK',200);
        }
        return response('Funcionario não encontrado',404);
    }
}
