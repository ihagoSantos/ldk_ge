@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-12">
                <div class="titulo-pagina">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="titulo-pagina-nome">
                                <h2>Cargos</h2>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-primary-ludke" role="button" onclick="novoCargo()">Novo</a>
                        </div>
                        <div class="col-md-4 input-group">
                            {{-- <input id="inputBusca" class="form-control input-ludke" type="text" placeholder="Pesquisar" name="pesquisar"> --}}
                            <form action="{{route('buscarCargo')}}" method="POST">
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
                <a href="{{route('cargos')}}">
                    <button class="btn btn-outline-danger">Listar Todos</button>
                </a>

            </div>
        </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if(isset($cargos))
                <table id="tabelaCargos" class="table table-hover table-responsive-md">
                    <thead class="thead-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{-- Linhas da tabela serão adicionadas com javascript --}}
                    
                    @foreach ($cargos as $cargo)
                    <tr>
                        <td>{{$cargo->id}}</td>
                        <td>{{$cargo->nome}}</td>
                        <td>
                            <a href="#" onclick="editarCargos({{$cargo->id}})">
                            <img id="iconeEdit" class="icone" src="{{asset('img/edit-solid.svg')}}" style="">
                            </a>
                            <a href="#" onclick="removerCargo({{$cargo->id}})">
                            <img id="iconeDelete" class="icone" src="{{asset('img/trash-alt-solid.svg')}}" style="">
                            </a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table> <!-- end table -->

            <div class="row justify-content-center">
                {{ $cargos->render() }}
            </div>
            @else
            <div class="row">
                <div class="col-sm-12 limparBusca">
                    <a href="{{route('cargos')}}">
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

    <div class="modal fade" tabindex="-1" role="dialog" id="dlgCargos">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formCargo" name="formCargo">
                    <div class="modal-header">
                        <h5 class="modal-title">Novo Cargo </h5>
                    </div>
                    <div class="modal-body">
                        {{-- ID da cargo --}}
                        <input type="hidden" id="id" class="form-control">

                        {{-- Nome do Cargo --}}
                        <div class="form-group">
                            <label for="nomeCargo" class="control-label">Nome do Cargo <span class="obrigatorio">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="nomeCargo" placeholder="Nome do Cargo" autofocus>
                            </div>
                            <div class="validationCargo"></div>
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
<script type="text/javascript">


        var token = <?php json_encode(Auth::user()->api_token); ?>
        console.log(token);
        $(function(){

            // Configuração do ajax com token csrf
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // carregarCargos();

            // ao exibir o modal, procura o input com autofocus e seleciona ele
            $('.modal').on('shown.bs.modal',function() {
                $(this).find('[autofocus]').focus();
            });
        });

        //function novaCategoria(){
        function novoCargo(){
            $('#id').val('');
            $('#nomeCargo').val('');

            $("#span").remove(); //remove a linha do span
            // exibe o modal cadastrar categorias
            $('#dlgCargos').modal('show');
        }

        function montarLinha(cat){
            //cria um html da linha da tabela
            var linha = "<tr>" +
                "<td>"+cat.id+"</td>"+
                "<td>"+cat.nome+"</td>"+
                "<td>"+
                "<a href="+"#"+" onclick="+"editarCargos("+cat.id+")"+">"+
                "<img id="+"iconeEdit"+" class="+"icone"+" src="+"{{asset('img/edit-solid.svg')}}"+" style="+""+">"+
                "</a>"+
                "<a href="+"#"+" onclick="+"removerCargo("+cat.id+")"+">"+
                "<img id="+"iconeDelete"+" class="+"icone"+" src="+"{{asset('img/trash-alt-solid.svg')}}"+" style="+""+">"+
                "</a>"+
                "</td>"+
                "</tr>";
            return linha;
        }
        function editarCargos(id){
            console.log(id);
            $.getJSON('/cargos/'+id, function(data){
                // console.log(data);
                $('#id').val(data.id);
                $('#nomeCargo').val(data.nome);

                $("#span").remove(); //remove a linha do span
                //exibe Modal Cadastrar Categoria
                $('#dlgCargos').modal('show');
            });
        }

        function removerCargo(id){
            confirma = confirm("Você tem certeza que deseja remover a categoria?");
            if(confirma){
                $.ajax({
                    type: "DELETE",
                    url: "/cargos/"+id,
                    context: this,
                    success: function(){
                        // console.log("deletou");
                        // linhas = $("#tabelaCargos>tbody>tr");
                        // e = linhas.filter(function(i,elemento){
                        //     return elemento.cells[0].textContent == id;//faz um filtro na linha e retorna a que tiver o id igual ao informado

                        // });
                        // if(e){
                        //     e.remove();
                        // }
                        alert("Cargo deletado com sucesso!")
                        window.location.href = '/indexCargos';
                    },
                    error: function(error){
                        console.log(error);
                    }

                });
            }

        }
        function carregarCargos(){
            $.getJSON('/cargos', function(cargos){

                for(i=0; i < cargos.length;i++){
                    linha = montarLinha(cargos[i]);
                    $('#tabelaCargos>tbody').append(linha);
                }
            });
        }

        function criarCargo(){
            cargo = {
                nome: $('#nomeCargo').val()
            };


            $.ajax({
            type: "POST",
            url: "/cargos",
            context:this,
            data:cargo,
            success: function(data){
                cargo = JSON.parse(data);
                alert("Cargo "+cargo.nome+" cadastrado com sucesso!");
                window.location.href = '/indexCargos';
                // cargo = JSON.parse(data);
                // linha = montarLinha(cargo);
                // $('#tabelaCargos>tbody').append(linha);
                // $('#dlgCargos').modal('hide');
                },
            error:function(error){
                retorno = JSON.parse(error.responseText);
                exibirErros(retorno.errors);

                }
            });
        }

        function exibirErros(error){
            $("#span").remove(); //remove a linha do span
            if(error){
                linha = "<span id="+"span"+" style="+"color:red"+">"+error.nome[0]+"</span>";
                $('.validationCargo').append(linha);
                console.log(error.nome[0]);
            }
        }
        function salvarCargo(){
            $("#span").remove(); //remove a linha do span
            cargo = {
                id: $('#id').val(),
                nome: $('#nomeCargo').val()
            };
            // faz requisição PUT para /api/categorias passando o id da categoria que deseja editar
            $.ajax({
                type: "PUT",
                url: "/cargos/"+cargo.id,
                context: this,
                data: cargo,
                success: function(data){
                    cargo = JSON.parse(data);
                    alert("Cargo "+cargo.nome+" salvo com sucesso!")
                    window.location.href = '/indexCargos';
                    // console.log(cargo);
                    // cargo = JSON.parse(data);
                    // console.log("salvou OK");
                    // linhas = $('#tabelaCargos>tbody>tr');
                    // $("#dlgCargos").modal('hide');
                    // e = linhas.filter(function(i,elemento){
                    //     return (elemento.cells[0].textContent == cargo.id);
                    // });
                    // console.log(e);
                    // if(e){
                    //     e[0].cells[0].textContent = cargo.id;
                    //     e[0].cells[1].textContent = cargo.nome;
                    // }
                    
                },
                error: function(error){
                    console.log(error);
                    retorno = JSON.parse(error.responseText);
                    exibirErros(retorno.errors);
                }
            });
        }
        $(function () {
            $('#formCargo').submit(function (event) {
                event.preventDefault();
                if($('#id').val()!= '')
                    salvarCargo();
                else
                    criarCargo();
                // $("#dlgCargos").modal('hide');

            })

        });



    </script>

@endsection
