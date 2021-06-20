@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">

        <div class="col-sm-12">
            <div class="titulo-pagina">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="titulo-pagina-nome">
                            <h2>Categorias</h2>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <a class="btn btn-primary-ludke" role="button" onclick="novaCategoria()">Novo</a>
                    </div>
                    <div class="col-md-4 input-group">
                        {{-- <input id="inputBusca" class="form-control input-ludke" type="text" placeholder="Pesquisar" name="pesquisar"> --}}
                        <form action="{{route('buscarCategoria')}}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input name="q" type="text" class="form-control" placeholder="Buscar Categoria">
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
            <a href="{{route('categorias')}}">
                <button class="btn btn-outline-danger">Listar Todos</button>
            </a>

        </div>
    </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-sm-12">
            @if(isset($categorias))
            <table id="tabelaCategorias" class="table table-hover table-responsive-sm">
                <thead class="thead-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Linhas da tabela serão adicionadas com javascript --}}
                    {{-- Linhas da tabela serão adicionadas com javascript --}}
                    @foreach ($categorias as $categoria)
                    <tr>
                        <td>{{$categoria->id}}</td>
                        <td>{{$categoria->nome}}</td>
                        <td>
                            <a href="#" onclick="editarCategoria({{$categoria->id}})">
                                <img id="iconeEdit" class="icone" src="{{asset('img/edit-solid.svg')}}" style="">
                            </a>
                            <a href="#" onclick="removerCategoria({{$categoria->id}})">
                                <img id="iconeDelete" class="icone" src="{{asset('img/trash-alt-solid.svg')}}" style="">
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table> <!-- end table -->

            <div class="row justify-content-center">
                {{ $categorias->render() }}
            </div>
            @else
            <div class="row">
                <div class="col-sm-12 limparBusca">
                    <a href="{{route('categorias')}}">
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

<div class="modal fade" tabindex="-1" role="dialog" id="dlgCategorias">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="formCategoria" name="formCategoria">
                <div class="modal-header">
                    <h5 class="modal-title">Nova Categoria</h5>
                </div>
                <div class="modal-body">
                    {{-- ID da categoria --}}
                    <input type="hidden" id="id" class="form-control">

                    {{-- Nome do Categoria --}}
                    <div class="form-group">
                        {{-- Div para validação --}}
                        <label for="nomeCategoria" class="control-label">Nome da Categoria <span class="obrigatorio">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="nomeCategoria" placeholder="Nome da Categoria" autofocus>

                        </div>
                        <div class="validationCategoria"></div>
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

        // carregarCategorias();

        // ao exibir o modal, procura o input com autofocus e seleciona ele
        $('.modal').on('shown.bs.modal',function() {
            $(this).find('[autofocus]').focus();
        });

    });

    //function novaCategoria(){
    function novaCategoria(){
        $('#id').val('');
        $('#nomeCategoria').val('');

        $("#span").remove(); //remove a linha do span
        // exibe o modal cadastrar categorias
        $('#dlgCategorias').modal('show');

        

    }

    function montarLinha(cat){
        //cria um html da linha da tabela
        var linha = "<tr>" +
                        "<td>"+cat.id+"</td>"+
                        "<td>"+cat.nome+"</td>"+
                        "<td>"+
                            "<a href="+"#"+" onclick="+"editarCategoria("+cat.id+")"+">"+
                                "<img id="+"iconeEdit"+" class="+"icone"+" src="+"{{asset('img/edit-solid.svg')}}"+" style="+""+">"+
                            "</a>"+
                            "<a href="+"#"+" onclick="+"removerCategoria("+cat.id+")"+">"+
                                "<img id="+"iconeDelete"+" class="+"icone"+" src="+"{{asset('img/trash-alt-solid.svg')}}"+" style="+""+">"+
                            "</a>"+
                        "</td>"+
                    "</tr>";
        return linha;
    }
    function editarCategoria(id){
        console.log('Editar Categoria');
        $.getJSON('/categorias/'+id, function(data){
            // console.log(data);
            $('#id').val(data.id);
            $('#nomeCategoria').val(data.nome);

            $("#span").remove(); //remove a linha do span
            //exibe Modal Cadastrar Categoria
            $('#dlgCategorias').modal('show');
        });
    }

    function removerCategoria(id){
        confirma = confirm("Você tem certeza que deseja remover a categoria?");
        if(confirma){
            $.ajax({
                type: "DELETE",
                url: "/categorias/"+id,
                context: this,
                success: function(){
                    alert("Categoria deletada com sucesso!");
                    window.location.href = '/indexCategorias';
                    // console.log("deletou");
                    // linhas = $("#tabelaCategorias>tbody>tr");
                    // e = linhas.filter(function(i,elemento){
                    //     return elemento.cells[0].textContent == id;//faz um filtro na linha e retorna a que tiver o id igual ao informado

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
    function carregarCategorias(){
        $.getJSON('/categorias', function(categorias){

            for(i=0; i < categorias.length;i++){
                linha = montarLinha(categorias[i]);
                $('#tabelaCategorias>tbody').append(linha);
            }
        });
    }

    function criarCategoria(){
        cat = {
            nome: $('#nomeCategoria').val()
        };
        $.ajax({
            type: "POST",
            url: "/categorias",
            context:this,
            data:cat,
            success: function(data){
                categoria = JSON.parse(data);
                $('#dlgCategorias').modal('hide');
                alert("Categoria "+categoria.nome+" cadastrada com sucesso!")
                window.location.href = '/indexCategorias';
                
                // categoria = JSON.parse(data);
                
                // linha = montarLinha(categoria);
                // $('#tabelaCategorias>tbody').append(linha);
                // $('#dlgCategorias').modal('hide');
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
            $('.validationCategoria').append(linha);
            console.log(error.nome[0]);

        }
        // for(i=0;i<error.length;i++){
        //     console.log(error[i]);
        // }
    }
    function salvarCategoria(){
        $("#span").remove(); //remove a linha do span
        cat = {
            id: $('#id').val(),
            nome: $('#nomeCategoria').val()
        };
        
        // faz requisição PUT para /api/categorias passando o id da categoria que deseja editar
        $.ajax({
            type: "PUT",
            url: "/categorias/"+cat.id,
            context: this,
            data: cat,
            success: function(data){
                cat = JSON.parse(data);
                alert("Categoria "+cat.nome+" salva com sucesso!")
                window.location.href = '/indexCategorias';

                // cat = JSON.parse(data);
                // console.log("salvou OK");
                // $('#dlgCategorias').modal('hide');
                // linhas = $('#tabelaCategorias>tbody>tr');
                // e = linhas.filter(function(i,elemento){
                //     return (elemento.cells[0].textContent == cat.id);
                // });
                // console.log(e);

                // if(e){
                //     e[0].cells[0].textContent = cat.id;
                //     e[0].cells[1].textContent = cat.nome;
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
       $('#formCategoria').submit(function (event) {
           event.preventDefault();
           if($('#id').val()!= '')
                salvarCategoria();
            else
                criarCategoria();
            // $("#dlgCategorias").modal('hide');

       })

    });



  </script>

@endsection
