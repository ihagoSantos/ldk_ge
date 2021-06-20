
@extends('layouts.app')

@section('content')



<div class="container">
    <div class="row justify-content-center">

        <div class="col-sm-12">
            <div class="titulo-pagina">
                <div class="row d-flex justify-content-between">
                    <div class="col-sm-7">
                        <div class="titulo-pagina-nome">
                            <h2>Clientes</h2>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        {{-- <a href="#" data-toggle="modal" data-target="#dlgFiltro"class="btn btn-primary-ludke">Filtrar</a> --}}
                        <button id="btnFiltrar" type="button" class="btn btn-primary-ludke" data-toggle="modal" data-target="#modalFiltro">
                            Filtrar
                        </button>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary-ludke" role="button" onclick="novoCliente()">Novo</button>
                    </div>




                    <div class="modal fade" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Filtrar Clientes</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{route('buscarCliente')}}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="cliente">Nome do Cliente</label>
                                                <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Nome do Cliente">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="cliente">Nome Reduzido</label>
                                                <input type="text" class="form-control" id="cliente" name="nomeReduzido" placeholder="Nome Reduzido">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger">Filtrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end titulo-pagina -->
        </div><!-- end col-->
    </div><!-- end row-->

    {{-- botão listar Todos --}}
    {{-- botão listar Todos --}}
    @if(isset($achou) && $achou == true)
        <div class="row" style="margin-bottom:10px">
            <div class="col-sm-12">
                <span class="badge badge-light" style="padding:5px"><h4>Filtro: {{$tipoFiltro}}</h4></span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 limparBusca">
                <a href="{{route('clientes')}}">
                    <button class="btn btn-outline-danger">Listar Todos</button>
                </a>
            </div>
        </div>
    @endif

    @if(isset($achou) && $achou == false)
        <div class="row" style="margin-bottom:10px">
            <div class="col-sm-12">
                <span class="badge badge-light" style="padding:5px"><h4>Nenhum cliente encontrado com esse filtro</h4></span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 limparBusca">
                <a href="{{route('clientes')}}">
                    <button class="btn btn-outline-danger">Listar Todos</button>
                </a>
            </div>
        </div>
    @endif



    <div class="row justify-content-center">
        <div class="col-sm-12">
            @if(isset($clientes))
            <table id="tabelaClientes" class="table table-hover table-responsive-xl">
                <thead class="thead-primary">
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Nome Reduzido</th>
                        <th>CPF/CNPJ</th>
                        <th>Cidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{$cliente->id}}</td>
                        <td>{{$cliente->user->name}}</td>
                        <td>{{$cliente->nomeReduzido}}</td>
                        <td>{{$cliente->cpfCnpj}}</td>
                        <td>{{$cliente->user->endereco->cidade}}</td>
                        <td>
                            <a href="#" onclick="editarCliente({{$cliente->id}})">
                               <img id="iconeEdit" class="icone" src="{{asset('img/edit-solid.svg')}}">
                            </a>
                            <a href="#" onclick="removerCliente({{$cliente->id}})">
                                <img id="iconeDelete" class="icone" src="{{asset('img/trash-alt-solid.svg')}}">
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table> <!-- end table -->


            @endif
        </div><!-- end col-->
    </div><!-- end row-->
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="dlgClientes">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="formCliente">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Novo Cliente</h5>
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
                                <label for="nomeCliente" class="control-label">Nome do Cliente <span class="obrigatorio">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nomeCliente" placeholder="Nome do Cliente" autofocus>
                                </div>
                                <div id="validationNome"></div>
                            </div>
                        </div>

                    </div>

                    {{-- Nome reduzido + nome responsável --}}
                    <div class="row justify-content-center">
                        {{-- Nome reduzido --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nomeReduzido" class="control-label">Nome Reduzido</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nomeReduzido" placeholder="Nome Reduzido">
                                </div>
                                <div id="validationNomeReduzido"></div>
                            </div>
                        </div>

                        {{-- Nome responsável --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nomeResponsavel" class="control-label">Nome do Responsável</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nomeResponsavel" placeholder="Nome do Responsável">
                                </div>
                                <div id="validationNomeResponsavel"></div>
                            </div>
                        </div>
                    </div>

                    {{-- row nome + cargo --}}
                    <div class="row justify-content-center">

                        {{-- cpf/cnpj --}}
                        <div class="col-sm-6">
                            
                            <div class="form-group" >
                                <label for="cpfCnpj" class="control-label">CPF/CNPJ <span class="obrigatorio">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="cpfCnpj" id="cpfCnpj" placeholder="CPF/CNPJ"/>
                                
                                </div>
                                <div id="validationCpfCnpj"></div>
                            </div>
                        </div>


                    {{-- Associar vendedor  --}}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="cargoFuncionario" class="control-label">Tipo <span class="obrigatorio">*</span></label>
                            <div class="input-group">
                                <select class="form-control" id="tipo">
                                    <option value="" disabled selected hidden>-- Tipo --</option>
                                    <option value="pessoaFisica">PESSOA FÍSICA</option>
                                    <option value="pessoaJuridica">PESSOA JURÍDICA</option>
                                </select>
                            </div>
                            <div id="validationTipo"></div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="cargoFuncionario" class="control-label">Funcionário Responsável<span class="obrigatorio"></span></label>
                            <div class="input-group">
                                <select class="form-control" id="funcionario_id" name="funcionario_id">

                                    <option value="" >Todos</option>
                                    @foreach($fun as $vendedor)
                                        <option value="{{$vendedor->id}}">{{$vendedor->user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="validationTipo"></div>
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
                                    <input type="text" class="form-control" id="celular" placeholder="Telefone Celular">
                                </div>
                                <div id="validationCelular"></div>
                            </div>
                        </div>

                    </div>

                    <div class="row justify-content-center">
                        {{-- inscricao estadual --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inscricaoEstadual" class="control-label">Inscrição Estadual</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="inscricaoEstadual" placeholder="Inscrição Estadual">
                                </div>
                                <div id="validationInscricaoEstadual"></div>
                            </div>
                        </div>

                        {{-- email do funcionário --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="emailCliente" class="control-label">E-mail do Cliente</label>
                                <div class="input-group">
                                    <input type="email" class="form-control" id="emailCliente" placeholder="E-mail do Cliente">
                                </div>
                                <div id="validationEmail"></div>
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
<script type="application/javascript">




    $(function(){
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        carregarEstados();
        // carregarClientes();

        // ao exibir o modal, procura o input com autofocus e seleciona ele
        $('.modal').on('shown.bs.modal',function() {
            $(this).find('[autofocus]').focus();
        });
    });
    

    
    function carregarClientes(){
        // console.log('clientes');
        $.getJSON('/clientes',function(clientes){
            for(i=0; i<clientes.length;i++){
                // console.log(clientes[i]);
                linha = montarLinha(clientes[i]);
                $('#tabelaClientes>tbody').append(linha);

            }
        });
    }



    function montarLinha(c){
        var linha = "<tr>"+
                        "<td>"+c.id+"</td>"+
                        "<td>"+c.nome+"</td>"+
                        "<td>"+c.cpfCnpj+"</td>"+
                        "<td>"+c.residencial+"</td>"+
                        "<td>"+c.celular+"</td>"+
                        "<td>"+
                            "<a href="+"#"+" onclick="+"editarCliente("+c.id+")"+">"+
                                "<img id="+"iconeEdit"+" class="+"icone"+" src="+"{{asset('img/edit-solid.svg')}}"+" style="+""+">"+
                            "</a>"+
                            "<a href="+"#"+" onclick="+"removerCliente("+c.id+")"+">"+
                                "<img id="+"iconeDelete"+" class="+"icone"+" src="+"{{asset('img/trash-alt-solid.svg')}}"+" style="+""+">"+
                            "</a>"+
                        "</td>"+
                    "</tr>";
        return linha;
    }

    function editarCliente(id){
        $.getJSON("/clientes/"+id, function(data){
            $('#id').val(data.id);
            $('#nomeCliente').val(data.nome);
            $('#emailCliente').val(data.email);
            $('#nomeReduzido').val(data.nomeReduzido);
            $('#nomeResponsavel').val(data.nomeResponsavel);
            $('#cpfCnpj').val(data.cpfCnpj);
            $('#tipo').val(data.tipo);
            $('#inscricaoEstadual').val(data.inscricaoEstadual);
            $('#funcionario_id').val(data.funcionario_id),
            $('#residencial').val(data.residencial);
            $('#celular').val(data.celular);
            $('#cep').val(data.cep);
            $('#rua').val(data.rua);
            $('#bairro').val(data.bairro);
            $('#cidade').val(data.cidade);
            $('#uf').val(data.uf);
            $('#numero').val(data.numero);
            $('#complemento').val(data.complemento);

            $(".span").remove(); //limpa os span de erro

            // exibe modal cadastrar Produtos
            $('#dlgClientes').modal('show');
        });
    }
    function removerCliente(id){
        confirma = confirm("Você tem certeza que deseja remover este cliente?");
        if(confirma){
            $.ajax({
                type: "DELETE",
                url: "/clientes/"+id,
                context: this,
                success: function(){
                    alert("Cliente deletado com sucesso!");
                    window.location.href="\indexClientes";
                    // console.log("Deletou Cliente");
                    // linhas = $("#tabelaClientes>tbody>tr");
                    // e = linhas.filter(function(i,elemento){
                    //     return elemento.cells[0].textContent== id;
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
    function novoCliente(){

        $('#id').val('');
        $('#nomeCliente').val('');
        $('#emailCliente').val('');
        $('#nomeReduzido').val('');
        $('#nomeResponsavel').val('');
        $('#cpfCnpj').val('');
        $('#tipo').val('');
        $('#inscricaoEstadual').val('');
        $('#funcionario_id').val('0');


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
        $('#dlgClientes').modal('show');
    }
    function salvarCliente(){
        console.log('Salvar Cliente');

        cliente = {
            id: $('#id').val(),
            nome: $('#nomeCliente').val(),
            email: $('#emailCliente').val(),
            nomeReduzido: $('#nomeReduzido').val(),
            nomeResponsavel: $('#nomeResponsavel').val(),
            cpfCnpj: $('#cpfCnpj').val(),
            funcionario_id:$('#funcionario_id').val(),
            tipo: $('#tipo').val().toUpperCase(),
            inscricaoEstadual: $('#inscricaoEstadual').val().toUpperCase(),
            residencial: $('#residencial').val().toUpperCase(),
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
            url: "/clientes/"+cliente.id,
            context: this,
            data: cliente,
            success: function(data){


                cli = JSON.parse(data);
                alert("Cliente salvo com sucesso!")
                window.location.href="/indexClientes";
                // cli = JSON.parse(data);
                // linhas = $('#tabelaClientes>tbody>tr');
                // e = linhas.filter(function(i,elemento){
                //     return (elemento.cells[0].textContent == cliente.id);
                // });
                // if(e){
                //     e[0].cells[0].textContent = cliente.id;
                //     e[0].cells[1].textContent = cliente.nome;
                //     e[0].cells[2].textContent = cliente.cpfCnpj;
                //     e[0].cells[3].textContent = cliente.residencial;
                //     e[0].cells[4].textContent = cliente.celular;

                // }
                // $('#dlgClientes').modal('hide');
            },
            error: function(error){
                    console.log(error);
                    retorno = JSON.parse(error.responseText);
                    exibirErros(retorno.errors);
                }
        });

    }

    function criarCliente(){
        //console.log('Criar Cliente');

        cliente = {
            nome: $('#nomeCliente').val(),
            email: $('#emailCliente').val(),
            nomeReduzido: $('#nomeReduzido').val(),
            nomeResponsavel: $('#nomeResponsavel').val(),
            cpfCnpj: $('#cpfCnpj').val(),

            funcionario_id:$('#funcionario_id').val(),

            tipo: $('#tipo').val().toUpperCase(),
            inscricaoEstadual: $('#inscricaoEstadual').val().toUpperCase(),


            residencial: $('#residencial').val(),
            celular: $('#celular').val(),
            cep: $('#cep').val(),
            rua: $('#rua').val(),
            bairro: $('#bairro').val(),
            cidade: $('#cidade').val(),
            uf: $('#uf').val(),
            numero: $('#numero').val(),

            complemento: $('#complemento').val().toUpperCase()
        }
        //console.log('511');


        //#console.log(cliente);
        $.ajax({
                type: "POST",
                url: "/clientes",
                context: this,
                data: cliente,
                success: function(data ){
                    // console.log("teste");
                    cliente = JSON.parse(data || "[]");
                    alert("Cliente cadastrado com sucesso!");
                    window.location.href="\indexClientes";
                    //linha = montarLinha(cliente);
                    //$('#tabelaClientes>tbody').append(linha);
                    //$('#dlgClientes').modal('hide');
                },
                error: function(error){
                    retorno = JSON.parse(error.responseText);
                    //#console.log(retorno);

                    exibirErros(retorno.errors);

                }

            });
    }
    function exibirErros(error){
        console.log(error);
        $(".span").remove(); //limpa os span de erro
        if(error){
            if(error.nome){
                for(i=0;i<error.nome.length;i++){
                    console.log(error.nome[i]);
                    $("#validationNome").append("<span class="+"span"+" style="+"color:red"+">"+error.nome[i]+"</span>");
                }
            }
            if(error.email){
                for(i=0;i<error.email.length;i++){
                    console.log(error.email[i]);
                    $("#validationEmail").append("<span class="+"span"+" style="+"color:red"+">"+error.email[i]+"</span>");
                }
            }
            if(error.cpfCnpj){
                for(i=0;i<error.cpfCnpj.length;i++){
                    console.log(error.cpfCnpj[i]);
                    $("#validationCpfCnpj").append("<span class="+"span"+" style="+"color:red"+">"+error.cpfCnpj[i]+"</span>");
                }
            }
            if(error.tipo){
                for(i=0;i<error.tipo.length;i++){
                    console.log(error.tipo[i]);
                    $("#validationTipo").append("<span class="+"span"+" style="+"color:red"+">"+error.tipo[i]+"</span>");
                }
            }

            if(error.funcionario_id){
                for(i=0;i<error.funcionario_id.length;i++){
                    console.log(error.funcionario_id[i]);
                    $("#validationfuncionario_id").append("<span class="+"span"+" style="+"color:red"+">"+error.funcionario_id[i]+"</span>");
                }
            }
            if(error.residencial){
                for(i=0;i<error.residencial.length;i++){
                    console.log(error.residencial[i]);
                    $("#validationResidencial").append("<span class="+"span"+" style="+"color:red"+">"+error.residencial[i]+"</span>");
                }
            }
            if(error.celular){
                for(i=0;i<error.celular.length;i++){
                    console.log(error.celular[i]);
                    $("#validationCelular").append("<span class="+"span"+" style="+"color:red"+">"+error.celular[i]+"</span>");
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
        $('#formCliente').submit(function(event){
            event.preventDefault();// não deixa fechar o modal quando clica no submit

            if($('#id').val()!= ''){

                salvarCliente();
            }
            else{

                criarCliente();
            }
            // $('#dlgClientes').modal('hide');
        });
    });



    $(document).ready(function($){ 
        try{
            $("#cpfCnpj").unmask();
            $("#cpfCnpj").keypress(function(){      
                var tamanho = $('#cpfCnpj').val().length;
                if(tamanho < 14){
                    $('#cpfCnpj').mask('999.999.999-99');

                }
            else if(tamanho >= 14){    
                    $('#cpfCnpj').mask('00.000.000/0000-00');

                }
                
            });
        }catch(e){

        }    
    });
                                    

</script>
@endsection
