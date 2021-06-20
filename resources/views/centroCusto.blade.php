@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">

        <div class="col-sm-12">
            <div class="titulo-pagina">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="titulo-pagina-nome">
                            <h2>Centro de Custo</h2>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <a class="btn btn-primary-ludke" role="button" onclick="novoCentroCusto()">Novo</a>
                    </div>
                    <div class="col-md-4 input-group">
                        {{-- <input id="inputBusca" class="form-control input-ludke" type="text" placeholder="Pesquisar" name="pesquisar"> --}}
                        <form action="{{route('buscarCentroCusto')}}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input name="q" type="text" class="form-control" placeholder="Buscar Centro de Custo">
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
            <a href="{{route('centroCusto')}}">
                <button class="btn btn-outline-danger">Listar Todos</button>
            </a>

        </div>
    </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-sm-12">
            @if(isset($centroCusto))
            <table id="tabelaCategorias" class="table table-hover table-responsive-sm">
                <thead class="thead-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Obs.</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Linhas da tabela serão adicionadas com javascript --}}
                    {{-- Linhas da tabela serão adicionadas com javascript --}}
                    @foreach ($centroCusto as $cc)
                    <tr>
                        <td>{{$cc->id}}</td>
                        <td>{{$cc->nome}}</td>
                        <td>{{$cc->obs}}</td>
                        <td>
                            <a href="#" onclick="editarCentroCusto({{$cc->id}})">
                                <img id="iconeEdit" class="icone" src="{{asset('img/edit-solid.svg')}}" style="">
                            </a>
                            <a href="#" onclick="removerCentrocusto({{$cc->id}})">
                                <img id="iconeDelete" class="icone" src="{{asset('img/trash-alt-solid.svg')}}" style="">
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table> <!-- end table -->

            <div class="row justify-content-center">
                {{ $centroCusto->render() }}
            </div>
            @else
            <div class="row">
                <div class="col-sm-12 limparBusca">
                    <a href="{{route('centroCusto')}}">
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

<div class="modal fade" tabindex="-1" role="dialog" id="dlgCentroCusto">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="formCategoria" name="formCategoria">
                <div class="modal-header">
                    <h5 class="modal-title">Novo Centro de Custo</h5>
                </div>
                <div class="modal-body">
                    {{-- ID da categoria --}}
                    <input type="hidden" id="id" class="form-control">

                    {{-- Nome do Categoria --}}
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="">Nome <span class="obrigatorio">*</span></label>
                            <input type="text" class="form-control" name="nomeCentroCusto" id="nomeCentroCusto">
                            <div class="validationCentroCusto"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="">Observação</label>
                            <textarea class="form-control" name="obsCentroCusto" id="obsCentroCusto" cols="30" rows="2"></textarea>
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

<script type="text/javascript">

    // Usa a biblioteca quicksearch para buscar dados na tabela
    // $('input#inputBusca').quicksearch('table#tabelaCategorias tbody tr');

    $(function(){

        // Configuração do ajax com token csrf
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // carregarCentroCusto();

        // ao exibir o modal, procura o input com autofocus e seleciona ele
        $('.modal').on('shown.bs.modal',function() {
            $(this).find('[autofocus]').focus();
        });

    });

    //function novoCentroCusto(){
    function novoCentroCusto(){
        $('#id').val('');
        $('#nomeCentroCusto').val('');
        $('#obsCentroCusto').val('');
        
        $("#span").remove(); //remove a linha do span
        // exibe o modal cadastrar categorias
        $('#dlgCentroCusto').modal('show');

    }


    function editarCentroCusto(id){
        console.log('Editar Centro de custo');
        $.getJSON('/centroCusto/'+id, function(data){
            // console.log(data);
            $('#id').val(data.id);
            $('#nomeCentroCusto').val(data.nome);
            $('#obsCentroCusto').val(data.obs);

            $("#span").remove(); //remove a linha do span
            //exibe Modal Cadastrar Categoria
            $('#dlgCentroCusto').modal('show');
        });
    }

    function removerCentrocusto(id){
        confirma = confirm("Você tem certeza que deseja remover o Centro de Custo?");
        if(confirma){
            $.ajax({
                type: "DELETE",
                url: "/centroCusto/"+id,
                context: this,
                success: function(){
                    alert("Centro de Custo deletado com sucesso!");
                    window.location.href = '/indexCentroCusto';
                },
                error: function(error){
                    console.log(error);
                }

            });
        }

    }

    function criarCentroCusto(){
        centroCusto = {
            nome: $('#nomeCentroCusto').val(),
            obs: $('#obsCentroCusto').val(),
        };
        console.log(centroCusto)
        $.ajax({
            type: "POST",
            url: "/centroCusto",
            context:this,
            data:centroCusto,
            success: function(data){
                categoria = JSON.parse(data);
                $('#dlgCentroCusto').modal('hide');
                alert("Centro de Custo cadastrado com sucesso!")
                window.location.href = '/indexCentroCusto';
                
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
            $('.validationCentroCusto').append(linha);
            console.log(error.nome[0]);

        }
        // for(i=0;i<error.length;i++){
        //     console.log(error[i]);
        // }
    }
    function salvarCentroCusto(){
        $("#span").remove(); //remove a linha do span
        centroCusto = {
            id: $('#id').val(),
            nome: $('#nomeCentroCusto').val(),
            obs: $('#obsCentroCusto').val(),
        };
        
        // faz requisição PUT para /api/categorias passando o id da categoria que deseja editar
        $.ajax({
            type: "PUT",
            url: "/centroCusto/"+centroCusto.id,
            context: this,
            data: centroCusto,
            success: function(data){
                cat = JSON.parse(data);
                alert("Centro de Custo salvo com sucesso!")
                window.location.href = '/indexCentroCusto';
            },
            error: function(error){
                console.log(error);
                retorno = JSON.parse(error.responseText);
                exibirErros(retorno.errors);
            }
        });
    }
   $(function () {
       $('#formCategoria').submit(function (event) {
           event.preventDefault();
           if($('#id').val()!= '')
                salvarCentroCusto();
            else
                criarCentroCusto();
            // $("#dlgCentroCusto").modal('hide');

       })

    });



  </script>

@endsection
