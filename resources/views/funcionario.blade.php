@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-sm-12">
            <div class="titulo-pagina">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="titulo-pagina-nome">
                            <h2>Funcionários</h2>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary-ludke" role="button" onclick="novoFuncionario()">Novo</button>
                    </div>
                    <div class="col-md-4 input-group">
                        {{-- <input id="inputBusca" class="form-control input-ludke" type="text" placeholder="Pesquisar" name="pesquisar"> --}}
                        <form action="{{route('buscarFuncionario')}}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input name="q" type="text" class="form-control" placeholder="Buscar Cargo" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                  <button class="btn btn-primary-ludke" type="submit">Buscar</button>
                                </div>
                              </div>
                        </form>

                    </div>
                </div>
            </div><!-- end titulo-pagina -->
        </div><!-- end col-->
    </div><!-- end row-->

    @if(isset($achou) && $achou == true)
    <div class="row">
        <div class="col-sm-12 limparBusca">
            <a href="{{route('funcionarios')}}">
                <button class="btn btn-outline-danger">Listar Todos</button>
            </a>

        </div>
    </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-sm-12">
            @if(isset($funcionarios))
            <table id="tabelaFuncionarios" class="table table-hover table-responsive-xl">
                <thead class="thead-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cargo</th>
                        <th>Email</th>
                        <th>Celular</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($funcionarios as $func)
                    <tr>
                       
                        <td>{{$func->id}}</td>
                        <td>{{$func->user->name}}</td>
                        <td>{{$func->cargo->nome}}</td>
                        <td>{{$func->user->email}}</td>
                        <td>{{$func->user->telefone->celular}}</td>
                        <td>
                            <a href="#" onclick="editarFuncionario({{$func->id}})">
                                <img id="iconeEdit" class="icone" src="{{asset('img/edit-solid.svg')}}">
                            </a>
                            <a href="#" onclick="removerFuncionario({{$func->id}})">
                                <img id="iconeDelete" class="icone" src="{{asset('img/trash-alt-solid.svg')}}">
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table> <!-- end table -->

            <div class="row justify-content-center">
                {{ $funcionarios->render() }}
            </div>
            @else
            <div class="row">
                <div class="col-sm-12 limparBusca">
                    <a href="{{route('funcionarios')}}">
                        <button class="btn btn-outline-danger">Listar Todos</button>
                    </a>

                </div>
            </div>
            {{-- Mensagem Alerta --}}
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="alert alert-danger" role="alert">
                        {{$menssage}}
                    </div>
                </div>
            </div>

            @endif
        </div><!-- end col-->
    </div><!-- end row-->
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="dlgFuncionarios">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="formFuncionario">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Novo Funcionário</h5>
                </div>
                <div class="modal-body">

                    {{-- ID do produto --}}
                    <input type="hidden" id="id" class="form-control">

                    {{-- row dados pessoais --}}
                    <div class="row justify-content-left">
                        <div class="col-sm-12">
                            <h3 id="categoriaForm">Dados Pessoais</h3>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        {{-- Nome do funcionário --}}
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="nomeFuncionario" class="control-label">Nome do Funcionario <span class="obrigatorio">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nomeFuncionario" placeholder="Nome do Funcionário" autofocus>
                                </div>
                                <div id="validationNome"></div>
                            </div>
                        </div>
                    </div>

                    {{-- row nome + cargo --}}
                    <div class="row justify-content-center">

                        {{-- Nome do funcionário --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="emailFuncionario" class="control-label">E-mail do Funcionario <span class="obrigatorio">*</span></label>
                                <div class="input-group">
                                    <input type="email" class="form-control" id="emailFuncionario" placeholder="E-mail do Funcionário">
                                </div>
                                <div id="validationEmail"></div>
                            </div>
                        </div>

                        {{-- Cargo do funcionário --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="cargoFuncionario" class="control-label">Cargo do Funcionário <span class="obrigatorio">*</span></label>
                                <div class="input-group">
                                    <select class="form-control" id="cargoFuncionario">
                                        <option value="" disabled selected hidden>-- Cargo --</option>
                                    </select>
                                </div>
                                <div id="validationCargo"></div>
                            </div>
                        </div>
                    </div>

                    {{-- row telefones --}}
                    <div class="row justify-content-center">

                        {{-- Nome do funcionário --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="residencial" class="control-label">Telefone Residêncial</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="residencial" placeholder="Telefone Residêncial">
                                </div>
                                <div id="validationResidencial"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            {{-- Nome do funcionário --}}
                            <div class="form-group">
                                <label for="celular" class="control-label">Telefone Celular</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="celular" placeholder="Telefone Celular 1">
                                </div>
                                <div id="validationCelular"></div>
                            </div>
                        </div>

                    </div>

                    {{-- row Endereço --}}
                    <div class="row justify-content-left">
                        <div class="col-sm-12">
                            <h3 id="categoriaForm">Endereço</h3>
                        </div>
                    </div>

                    {{-- row rua + CEP --}}
                    <div class="row justify-content-center">

                        {{-- CEP--}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="cep" class="control-label">CEP</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cep" placeholder="CEP">
                                </div>
                                <div id="validationCep"></div>
                            </div>
                        </div>

                        {{-- Rua--}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="rua" class="control-label">Rua <span class="obrigatorio">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="rua" placeholder="Rua">
                                </div>
                                <div id="validationRua"></div>
                            </div>
                        </div>


                    </div>

                    {{-- row bairro + cidade + UF --}}
                    <div class="row justify-content-center">
                        {{-- Bairro--}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="bairro" class="control-label">Bairro <span class="obrigatorio">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="bairro" placeholder="Bairro">
                                </div>
                                <div id="validationBairro"></div>
                            </div>
                        </div>

                        {{-- Cidade--}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="cidade" class="control-label">Cidade <span class="obrigatorio">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cidade" placeholder="Cidade">
                                </div>
                                <div id="validationCidade"></div>
                            </div>
                        </div>


                    </div>
                    {{-- row UF + Número --}}
                    <div class="row justify-content-center">
                        {{-- UF--}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="uf" class="control-label">UF <span class="obrigatorio">*</span></label>
                                <div class="input-group">
                                    <select class="form-control" id="uf">
                                        <option value="" disabled selected hidden>-- UF --</option>
                                    </select>
                                </div>
                                <div id="validationUf"></div>
                            </div>
                        </div>

                        {{-- Número--}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numero" class="control-label">Número <span class="obrigatorio">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="numero" placeholder="Número">
                                </div>
                                <div id="validationNumero"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        {{-- UF--}}
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="complemento" class="control-label">Complemento</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="complemento" placeholder="Complemento">
                                </div>
                                <div id="validationComplemento"></div>
                            </div>
                        </div>
                    </div>
                </div><!-- end modal body-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Cadastrar</button>
                    <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>

    //essa função é chamada sempre que atualiza a pagina
    $(function(){
        // Configura o ajax para todas as requisições ir com token csrf
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        carregarCargos();
        // carregarFuncionarios();
        carregarEstados();

        // ao exibir o modal, procura o input com autofocus e seleciona ele
        $('.modal').on('shown.bs.modal',function() {
            $(this).find('[autofocus]').focus();
        });
    });

    function carregarFuncionarios(){
        // alert('funcionarios');
        console.log('funcionarios');
        $.getJSON('/funcionarios',function(funcionarios){
            // console.log(funcionarios);
            for(i=0; i<funcionarios.length;i++){

                linha = montarLinha(funcionarios[i]);
                $('#tabelaFuncionarios>tbody').append(linha);
            }
        });
    }

    // lista de estados para o select UF
    function carregarEstados(){
        let estados = [
            'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
            'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
            'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
            ];
            for(i = 0; i < estados.length; i++){
                opcao = '<option value="'+estados[i]+'">'+estados[i]+'</option>'
                // console.log(opcao);
                $('#uf').append(opcao);
            }
    }
    function carregarCargos(){
        $.getJSON('/cargos',function(data){
            // console.log(data);

            for(i = 0; i < data.length; i++){
                opcao = '<option value="'+data[i].id+'">'+data[i].nome+'</option>'
                $('#cargoFuncionario').append(opcao);
            }
        });
    }

    function novoFuncionario(){
        // Limpa campos do modal
        $('#id').val('');
        $('#emailFuncionario').val('');
        $('#nomeFuncionario').val('');
        $('#cargoFuncionario').val('');

        $('#residencial').val('');
        $('#celular').val('');
        $('#cep').val('');
        $('#rua').val('');
        $('#bairro').val('');
        $('#cidade').val('');
        $('#uf').val('');
        $('#numero').val('');
        $('#complemento').val('');

        $(".span").remove(); //limpa os span de erro

        // exibe modal cadastrar Produtos
        $('#dlgFuncionarios').modal('show');
    }

    function salvarFuncionario(){
       // console.log('SalvarFuncionario');

        funcionario = {
            id: $('#id').val(),
            email: $('#emailFuncionario').val(),
            nome: $('#nomeFuncionario').val(),
            cargo: $('#cargoFuncionario').val(),
            residencial: $('#residencial').val(),
            celular: $('#celular').val(),
            cep: $('#cep').val(),
            rua: $('#rua').val(),
            bairro: $('#bairro').val(),
            cidade: $('#cidade').val(),
            uf: $('#uf').val(),
            numero: $('#numero').val(),
            complemento: $('#complemento').val()
        }

        $.ajax({
            type: "PUT",
            url: "/funcionarios/"+funcionario.id,
            context: this,
            data: funcionario,
            success: function(data){
                func = JSON.parse(data);
                alert("Funcionário "+func.nome+" salvo com sucesso!");
                window.location.href="indexFuncionarios";
                // func = JSON.parse(data);
                // linhas = $('#tabelaFuncionarios>tbody>tr');
                // e = linhas.filter(function(i,elemento){
                //     return (elemento.cells[0].textContent == funcionario.id);
                // });
                // if(e){
                //     e[0].cells[0].textContent = funcionario.id;
                //     e[0].cells[1].textContent = funcionario.nome;
                //     e[0].cells[2].textContent = funcionario.cargo;
                //     e[0].cells[3].textContent = funcionario.email;
                //    /* e[0].cells[4].textContent = funcionario.celular;
                //     e[0].cells[5].textContent = funcionario.cep;
                //     e[0].cells[6].textContent = funcionario.rua;
                //     e[0].cells[7].textContent = funcionario.numero;
                //     e[0].cells[8].textContent = funcionario.bairro;
                //     e[0].cells[9].textContent = funcionario.cidade;
                //     e[0].cells[10].textContent = funcionario.uf;
                //     e[0].cells[11].textContent = funcionario.complemento;*/

                // }
                // $('#dlgFuncionarios').modal('hide');
            },
            error: function(error){
                    console.log(error);
                    retorno = JSON.parse(error.responseText);
                    exibirErros(retorno.errors);
                }
        });
    }

     // cria um html da linha da tabela
     function montarLinha(f){
        var linha = "<tr>"+
                        "<td>"+f.id+"</td>"+
                        "<td>"+f.nome+"</td>"+
                        "<td>"+f.cargo+"</td>"+
                        "<td>"+f.email+"</td>"+
                        "<td>"+f.celular+"</td>"+
                        //"<td>"+f.cep+"</td>"+
                        //"<td>"+f.rua+"</td>"+
                        //"<td>"+f.numero+"</td>"+
                        //"<td>"+f.bairro+"</td>"+
                        //"<td>"+f.cidade+"</td>"+
                        //"<td>"+f.uf+"</td>"+
                        //"<td>"+f.complemento+"</td>"+
                        "<td>"+
                            "<a href="+"#"+" onclick="+"editarFuncionario("+f.id+")"+">"+
                                "<img id="+"iconeEdit"+" class="+"icone"+" src="+"{{asset('img/edit-solid.svg')}}"+" style="+""+">"+
                            "</a>"+
                            "<a href="+"#"+" onclick="+"removerFuncionario("+f.id+")"+">"+
                                "<img id="+"iconeDelete"+" class="+"icone"+" src="+"{{asset('img/trash-alt-solid.svg')}}"+" style="+""+">"+
                            "</a>"+
                        "</td>"+
                    "</tr>";
        return linha;
    }
    function editarFuncionario(id){
        console.log("editar Funcionario");
        $.getJSON("/funcionarios/"+id, function(data){

            $('#id').val(data.id);
            $('#emailFuncionario').val(data.email),
            $('#nomeFuncionario').val(data.nome),
            $('#cargoFuncionario').val(data.cargo),
            $('#residencial').val(data.residencial),
            $('#celular').val(data.celular),
            $('#cep').val(data.cep),
            $('#rua').val(data.rua),
            $('#bairro').val(data.bairro),
            $('#cidade').val(data.cidade),
            $('#uf').val(data.uf),
            $('#numero').val(data.numero),
            $('#complemento').val(data.complemento)

            $(".span").remove(); //limpa os span de erro

            // exibe modal cadastrar Produtos
            $('#dlgFuncionarios').modal('show');
        });
    }
    function removerFuncionario(id){
        console.log("Remover Funcionario");
        confirma = confirm("Você tem certeza que deseja remover este funcionário?");
        if(confirma){
            $.ajax({
                type: "DELETE",
                url: "/funcionarios/"+id,
                context: this,
                success: function(){
                    alert("Funcionário deletado com sucesso!");
                    window.location.href="\indexFuncionarios";
                    // console.log("Deletou Funcionario");
                    // linhas = $("#tabelaFuncionarios>tbody>tr");
                    // e = linhas.filter(function(i,elemento){
                    //     return elemento.cells[0].textContent == id;
                    // });
                    // if(e){
                    //     e.remove();
                    // }
                },
                error: function(error){
                    console.log(error);
                }
            });
        }


    }
    function criarFuncionario(){
        console.log('criarFuncionario');
        funcionario = {
            email: $('#emailFuncionario').val(),
            nome: $('#nomeFuncionario').val(),
            cargo: $('#cargoFuncionario').val(),
            residencial: $('#residencial').val(),
            celular: $('#celular').val(),
            cep: $('#cep').val(),
            rua: $('#rua').val(),
            bairro: $('#bairro').val(),
            cidade: $('#cidade').val(),
            uf: $('#uf').val(),
            numero: $('#numero').val(),
            complemento: $('#complemento').val()
        }


        $.ajax({
            type: "POST",
            url: "/funcionarios",
            context:this,
            data:funcionario,
            success: function(data){
                funcionario = JSON.parse(data);
                alert("Funcionário "+funcionario.nome+" cadastrado com sucesso!");
                window.location.href="\indexFuncionarios";
            //     funcionario = JSON.parse(data);
            //     linha = montarLinha(funcionario);
            //     $('#dlgFuncionarios').modal('hide');
            // $('#tabelaFuncionarios>tbody').append(linha);
            },
            error:function(error){
                retorno = JSON.parse(error.responseText);
                exibirErros(retorno.errors);
                console.log(error);

            }
        });

    }

    function exibirErros(error){
        $(".span").remove(); //limpa os span de erro
        if(error){
            if(error.nome){
                for(i=0;i<error.nome.length;i++){
                    console.log(error.nome[i]);
                    $("#validationNome").append("<span class="+"span"+" style="+"color:red"+">"+error.nome[i]+"</span>")
                }
            }
            if(error.email){
                for(i=0;i<error.email.length;i++){
                    console.log(error.email[i]);
                    $("#validationEmail").append("<span class="+"span"+" style="+"color:red"+">"+error.email[i]+"</span>")
                }
            }
            if(error.cargo){
                for(i=0;i<error.cargo.length;i++){
                    console.log(error.cargo[i]);
                    $("#validationCargo").append("<span class="+"span"+" style="+"color:red"+">"+error.cargo[i]+"</span>")
                }
            }
            if(error.residencial){
                for(i=0;i<error.residencial.length;i++){
                    console.log(error.residencial[i]);
                    $("#validationResidencial").append("<span class="+"span"+" style="+"color:red"+">"+error.residencial[i]+"</span>")
                }
            }
            if(error.celular){
                for(i=0;i<error.celular.length;i++){
                    console.log(error.celular[i]);
                    $("#validationCelular").append("<span class="+"span"+" style="+"color:red"+">"+error.celular[i]+"</span>")
                }
            }
            if(error.cep){
                for(i=0;i<error.cep.length;i++){
                    console.log(error.cep[i]);
                    $("#validationCep").append("<span style="+"color:red"+">"+error.cep[i]+"</span>")
                }
            }
            if(error.rua){
                for(i=0;i<error.rua.length;i++){
                    console.log(error.rua[i]);
                    $("#validationRua").append("<span class="+"span"+" style="+"color:red"+">"+error.rua[i]+"</span>")
                }
            }
            if(error.bairro){
                for(i=0;i<error.bairro.length;i++){
                    console.log(error.bairro[i]);
                    $("#validationBairro").append("<span class="+"span"+" style="+"color:red"+">"+error.bairro[i]+"</span>")
                }
            }
            if(error.cidade){
                for(i=0;i<error.cidade.length;i++){
                    console.log(error.cidade[i]);
                    $("#validationCidade").append("<span class="+"span"+" style="+"color:red"+">"+error.cidade[i]+"</span>")
                }
            }
            if(error.uf){
                for(i=0;i<error.uf.length;i++){
                    console.log(error.uf[i]);
                    $("#validationUf").append("<span class="+"span"+" style="+"color:red"+">"+error.uf[i]+"</span>")
                }
            }
            if(error.numero){
                for(i=0;i<error.numero.length;i++){
                    console.log(error.numero[i]);
                    $("#validationNumero").append("<span class="+"span"+" style="+"color:red"+">"+error.numero[i]+"</span>")
                }
            }
            if(error.complemento){
                for(i=0;i<error.complemento.length;i++){
                    console.log(error.complemento[i]);
                    $("#validationComplemento").append("<span class="+"span"+" style="+"color:red"+">"+error.complemento[i]+"</span>")
                }
            }

        }
    }
    $(function(){
        $('#formFuncionario').submit(function(event){
            event.preventDefault();// não deixa fechar o modal quando clica no submit
            if($('#id').val()!= ''){
                console.log('Salvar Funcionário');
                salvarFuncionario();
            }
            else{
                console.log('Criar Funcionário');
                criarFuncionario();
            }
            // $('#dlgFuncionarios').modal('hide');
        });
    });
</script>
@endsection
